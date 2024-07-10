<?php
    require_once("./header.php");
?>
<style>
    td{
        padding: 10px;
    }

    .hid_item_detail{
        display: none;
    }
</style>
<div class="container container_margin">
    <!-- <form id="myfrom" name="myform" method='POST'> -->
        <center>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center;">Searchable Sample</th>
                        </tr>
                    </thead>
                    <tr>
                        <td><label>Employee  : </label></td>
                        <td><input type="text" class="input" id="txt_employee"/></td>
                    </tr>
                    <tr><td></td><td></td></tr>
                    <tr>
                        <td><label>Item  : </label></td>
                        <td><input type="text" class="input" id="txt_item1"/></td>
                    </tr>
                    <tr>
                        <td>Additional Item Info  : </td>
                        <td><input type="text" class="input" id="txt_item2" readonly disabled/></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; vertical-align: middle;">
                            <input type="button" onclick="toggle_item_details(this)" value="Show Hidden Item Details"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; vertical-align: middle;">
                            <input type="text" class="input hid_item_detail" id="hid_txt_item1" readonly disabled/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; vertical-align: middle;">
                            <input type="text" class="input hid_item_detail" id="hid_txt_item2" readonly disabled/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; vertical-align: middle;">
                            <input type="text" class="input hid_item_detail" id="hid_item_brndsc" readonly disabled/>
                        </td>
                    </tr>
                    <tr><td></td><td></td></tr>
                </table>
        </center>
    <!-- </form> -->
</div>
<script>
    $(document).ready(function() {
        $("#txt_employee").searchable({
            modalTitle: 'Search Employee',
            modalWidth: 1050,
            modalHeight: 500,
            table: 'employeefile',
            tableCol: 'fullname',
            tableColHeader: 'Employee Fullname',
            link_id: 'link_id',
            searchCol: 'fullname', // value needed to be fetched - value inserted on input tag after select
            searchByDesc: ['Employee Fullname'],
            searchByValue: ['fullname'],
            sqlfilter: "",
            passValueTo: [],
            passValue: [],
            orderBy: 'fullname'
        });

        $("#txt_item1").searchable({
            modalTitle: 'Search Item',
            modalWidth: 1050,
            modalHeight: 500,
            table: 'itemfile',
            tableCol: 'itmcde,itmdsc', // space after comma is not allowed - will result to blank data after comma
            tableColHeader: 'Item Code, Item Description',
            link_id: 'link_id',
            searchCol: 'itmcde', // value needed to be fetched - value inserted on input tag after select
            searchByDesc: ['Item Code', 'Item Description'],
            searchByValue: ['itmcde', 'itmdsc'],
            sqlfilter: "",
            passValueTo: ["txt_item2", "hid_txt_item1", "hid_txt_item2","hid_item_brndsc"],
            passValue: ["itmdsc", "itmcde", "itmdsc", "brndsc"],
            orderBy: 'itmcde'
        })
        .on("change", function() {
            if($(this).val()) {
                const xhid_txt_item1 = $("#hid_txt_item1").val();
                const xhid_txt_item2 = $("#hid_txt_item2").val();
                const xhid_item_brndsc = $("#hid_item_brndsc").val();
                alertify.alert(`First hidden pass value to:  <b>${xhid_txt_item1}</b>
                                <br>Second hidden pass value to:  <b>${xhid_txt_item2}</b>
                                <br>Third hidden pass value to:  <b>${xhid_item_brndsc}</b>`);
            } else {
                $("#txt_item2").val("");
                $("#hid_txt_item1").val("");
                $("#hid_txt_item2").val("");
                $("#hid_item_brndsc").val("");
            }
        });
    });

    function toggle_item_details(elem) {
        if(elem.value.includes("Show")) {
            elem.value = elem.value.replace("Show", "Hide");
        } else {
            elem.value = elem.value.replace("Hide", "Show");
        }
        $(".hid_item_detail").toggle();
    }
</script>
<?php
    require_once("./footer.php");
?>