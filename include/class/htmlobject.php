<?php 
	
	class HtmlObject {

		public $link = '';
		public $type;
		public $fieldname;
		public $select_table;
		public $desc;
		public $val;
		public $filter;
		public $params;
		public $orderby;
		public $groupby;
		public $selected_index;
		public $checked;
		public $format;
		public $decimal_format;
		public $css;
		public $field_handler_table;
		public $field_handler_col;
		public $field_handler_qry;
		public $field_handler_params;


		// added 20171102 -jep
		public $qs_fromto = false;

		//link
		// public $link;


		//hidden
		public $hidden_desc;
		public $hidden_type;
		public $hidden_css;
		public $readonly = FALSE;
		
		//modal
		public $modal_display = TRUE;

		//check
		public $select_id;
		public $select_name;
		public $select_class;
		public $select_event;
		public $select_optionval;
		public $select_optiondesc;
		public $apply_btn_id;
		public $apply_btn_class;
		public $apply_btn_name;
		public $apply_btn_event;
		public $apply_btn_value;

		public function __construct(){

		}
	}
?>