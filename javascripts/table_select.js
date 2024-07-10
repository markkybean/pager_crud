/* Copyright (c) 2007 Miguel Manese 
 * Depends on prototype, scriptaculous
 */

Event.F2 = 113;

TableSelect = Class.create();
Object.extend(TableSelect.prototype, {
    initialize: function(refelem, update, url, options) {
        this.url = url;
        this.update = (typeof(update) == 'string') ? $(update) : update;
        this.refelem = (typeof(refelem) == 'string') ? $(refelem) : refelem;        
        this.visible = false;
        this.lastSelected = null;
        this.onTableSelect = options.onTableSelect;
        this.post = options.post;
        this.paramName = options.paramName || this.refelem.name || this.refelem.id;
        this.extraQueryParams = options.extraQueryParams;
        if (typeof(this.extraQueryParams) == 'string')
            this.extraQueryParams = [this.extraQueryParams];
        this.active = true;
        this.extraArg = options.extraArg;

        this.refelem.__tableSelect__ = this;

        Element.hide(this.update);
        this.__onHover = this.onHover.bindAsEventListener(this);
        this.__onClick = this.onClick.bindAsEventListener(this);
        Event.observe(this.update, "mouseover", this.__onHover);
        Event.observe(this.update, "click", this.__onClick);
        Event.observe(this.refelem, "keypress", this.onKeyPress.bindAsEventListener(this));
        Event.observe(this.refelem, "blur", this.hide.bindAsEventListener(this));
        new Form.Element.DelayedObserver(this.refelem, 0.5, 
            function(elem, value) {
                var tableSelect = elem.__tableSelect__;
                if (tableSelect.active) {
                    tableSelect.show({q: value});
                } else {
                    tableSelect.active = true;
                }
            });
    },

    getOptions: function(url_args) {
        if (this.post) {
            new Ajax.Request(this.url, 
                {onComplete:this.onComplete.bindAsEventListener(this),
                 parameters:Hash.toQueryString(url_args)});
        } else {
            new Ajax.Request(this.url, 
                {onComplete:this.onComplete.bindAsEventListener(this),
                 method:'get', parameters:Hash.toQueryString(url_args)});
        }
        this.lastSelected = null;
    },

    finalSelect: function(tr) {        
        if (!tr) return;
        var obj = tr.getAttribute('item_obj');
        //alert('FINAL SELECT: ' + obj);
        if (obj) { 
            obj = eval('('+obj+')'); // json
        }
        this.selectedObject = obj;
        if (this.onTableSelect && obj) {
            this.onTableSelect(obj, this.extraArg, this);
        }
    }, 

    select: function(tr) {
        if (this.lastSelected) {
            this.lastSelected.removeClassName('selected');
        }

        if (tr && typeof(tr) != 'undefined' && !Element.hasClassName(tr, 'header')) {
            tr.addClassName('selected');
            this.lastSelected = tr;
            //this.update.firstChild.focus();
        }
    },

    selectOffset: function(ofs) {
        var sel = this.lastSelected, idx;
        if (sel) {
            idx = parseInt(sel.getAttribute('index'));
            idx += ofs;
        } else idx = 0;

        if (idx < this.rows.length && idx >= 0) {
            this.select(this.rows[idx]);
        }
    },

    onComplete: function(req) {
        this.update.innerHTML = req.responseText;        
        this.rows = new Array();
        //alert('rows updated');

        var rows = this.update.getElementsByTagName('tr');
        var i, j;
        for (i = j = 0; i < rows.length; i++) {
            if (!Element.hasClassName(rows[i], 'header')) {
                this.rows[j] = rows[i];
                rows[i].setAttribute('index', j);
                j++;
            }
        }

        if (!this.__childOnKeypress)
            this.__childOnKeypress = this.onKeyPress.bindAsEventListener(this);

        Event.observe(this.update.firstChild, "keypress", this.__childOnKeypress);                

        this.update.style.position = 'absolute';
        
        var refelem = this.refelem;        

        Position.clone(refelem, this.update, 
            {setHeight: false, setWidth:false, offsetTop: refelem.offsetHeight+3});
        Effect.Appear(this.update,{duration:0.15});
        this.visible = true;

        if(!this.iefix && (navigator.appVersion.indexOf('MSIE')>0) &&
              (navigator.userAgent.indexOf('Opera')<0) &&
              (Element.getStyle(this.update, 'position')=='absolute')) {
            new Insertion.After(this.update, 
            '<iframe id="' + this.update.id + '_iefix" '+
            'style="display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" ' +
            'src="javascript:false;" frameborder="0" scrolling="no"></iframe>');
            this.iefix = $(this.update.id+'_iefix');
        }

        if(this.iefix) setTimeout(this.fixIEOverlapping.bind(this), 50);        
    },

    onHover: function(event) {
        var tr = Event.findElement(event, 'TR');
        if (tr.tagName == 'TR') this.select(tr);
    },

    onKeyPress: function(event) {
        if (this.visible) {
            switch(event.keyCode) {
                case Event.KEY_RETURN:
                case Event.KEY_TAB: 
                    this.active = false;
                    this.hide();
                    this.finalSelect(this.lastSelected);
                    Event.stop(event); 
                    break;
                case Event.KEY_ESC:
                    this.active = false;
                    this.hide();
                    Event.stop(event); break;
                case Event.KEY_UP:
                    this.selectOffset(-1);
                    Event.stop(event); break;
                case Event.KEY_DOWN:
                    this.selectOffset(1);
                    Event.stop(event); break;
            }
        } else {
            if (event.keyCode == Event.F2) this.show();
        }
    },

    onClick: function(event) {
        var tr = Event.findElement(event, 'TR');
        this.hide();        
        if (tr && typeof(tr) != 'undefined' && !tr.hasClassName('header')) {
            this.finalSelect(tr);            
        } 
    },

    show: function() { 
        url_args = {};
        url_args[this.paramName] = $F(this.refelem);

        if (this.extraQueryParams) {
            var key, value, eqp = this.extraQueryParams;
            if (eqp.constructor == Array) {
                var i;
                for (i = 0; i < eqp.length; i++) {
                    value = eqp[i];
                    key = (typeof(value) == 'string') ? value : ('q' + i);
                    url_args[key] = $F(value);

                }
            } else if (eqp.constructor == Object) {
            }
        }

        this.__show = true;        
        this.__url_args = url_args;
        this.getOptions(url_args);                 
    },

    fixIEOverlapping: function() {
        Position.clone(this.update, this.iefix);
        this.iefix.style.zIndex = 1;
        this.update.style.zIndex = 2;
        Element.show(this.iefix);
    },

    hide: function() { 
        Effect.Fade(this.update,{duration:0.15});
        this.visible = false;
        if (this.iefix) Element.hide(this.iefix);
    },

    finalize: function() {
        Event.stopObserving(this.update, "mouseover", this.__onHover);
        Event.stopObserving(this.update, "click", this.__onClick);
        if (this.__childOnKeypress && this.update.firstChild) {
            Event.stopObserving(this.update.firstChild, 'keypress', 
                                this.__childOnKeypress);
        }
    }
});


