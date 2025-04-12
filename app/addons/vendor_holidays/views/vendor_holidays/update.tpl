{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="vendor_holidays_form" class="form-horizontal form-edit">

<input type="hidden" name="holiday_id" value="{$holiday.holiday_id}" />

{capture name="tabsbox"}
<div id="content_general">
    <fieldset>
        <div class="control-group">
            <label class="control-label" for="elm_holiday_start_date">{__("start_date")}:</label>
            <div class="controls">
                {include file="common/calendar.tpl" 
                    date_id="elm_holiday_start_date" 
                    date_name="holiday_data[start_date]" 
                    date_val=$holiday.start_date 
                    start_year=$settings.Company.company_start_year}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="elm_holiday_end_date">{__("end_date")}:</label>
            <div class="controls">
                {include file="common/calendar.tpl" 
                    date_id="elm_holiday_end_date" 
                    date_name="holiday_data[end_date]" 
                    date_val=$holiday.end_date 
                    start_year=$settings.Company.company_start_year}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="elm_holiday_status">{__("status")}:</label>
            <div class="controls">
                <select name="holiday_data[status]" id="elm_holiday_status">
                    <option value="A" {if $holiday.status == "A"}selected="selected"{/if}>{__("active")}</option>
                    <option value="D" {if $holiday.status == "D"}selected="selected"{/if}>{__("disabled")}</option>
                </select>
            </div>
        </div>
    </fieldset>
</div>
{/capture}

{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true}

<div class="buttons-container">
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_holidays.update]" but_role="submit-link" but_onclick="$('#vendor_holidays_form').submit();" save=$holiday.holiday_id}
</div>

</form>

{/capture}

{include file="common/mainbox.tpl" 
    title=($holiday) ? $holiday.holiday_id : __("new_holiday")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 