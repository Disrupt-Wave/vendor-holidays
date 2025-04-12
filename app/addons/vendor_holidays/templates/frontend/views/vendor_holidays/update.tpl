{* Vendor Holiday Update Page - Frontend *}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="vendor_holiday_form" class="form-horizontal form-edit">

<input type="hidden" name="holiday_id" value="{$holiday.holiday_id}" />

{capture name="tabsbox"}

<div id="content_general">
    <fieldset>
        <div class="control-group">
            <label class="control-label" for="elm_holiday_date_from">{__("date_from")}:</label>
            <div class="controls">
                {include file="common/calendar.tpl" 
                    date_id="elm_holiday_date_from" 
                    date_name="holiday_data[date_from]" 
                    date_val=$holiday.date_from 
                    start_year=$settings.Company.company_start_year
                }
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="elm_holiday_date_to">{__("date_to")}:</label>
            <div class="controls">
                {include file="common/calendar.tpl" 
                    date_id="elm_holiday_date_to" 
                    date_name="holiday_data[date_to]" 
                    date_val=$holiday.date_to 
                    start_year=$settings.Company.company_start_year
                }
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
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_holidays.update]" but_role="submit-link" but_target_form="vendor_holiday_form" save=$holiday.holiday_id}
</div>

</form>

{/capture}

{include file="common/mainbox.tpl" 
    title=($holiday.holiday_id) ? $holiday.holiday_id : __("new_holiday") 
    content=$smarty.capture.mainbox 
    buttons=$smarty.capture.buttons
} 