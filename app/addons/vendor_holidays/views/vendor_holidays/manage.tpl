{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="vendor_holidays_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_vendor_holidays"}

{if $holidays}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
    <thead>
        <tr>
            <th>{__("start_date")}</th>
            <th>{__("end_date")}</th>
            <th>{__("status")}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    {foreach from=$holidays item=holiday}
        <tr>
            <td data-th="{__("start_date")}">{$holiday.start_date|date_format:$settings.Appearance.date_format}</td>
            <td data-th="{__("end_date")}">{$holiday.end_date|date_format:$settings.Appearance.date_format}</td>
            <td data-th="{__("status")}">
                {if $holiday.status == "A"}
                    <span class="label label-success">{__("active")}</span>
                {else}
                    <span class="label label-default">{__("disabled")}</span>
                {/if}
            </td>
            <td class="right nowrap" data-th="{__("tools")}">
                {capture name="tools_list"}
                    <li>{btn type="list" text=__("edit") href="vendor_holidays.update?holiday_id=`$holiday.holiday_id`"}</li>
                    <li>{btn type="list" class="cm-confirm" text=__("delete") href="vendor_holidays.delete?holiday_id=`$holiday.holiday_id`" method="POST"}</li>
                {/capture}
                <div class="hidden-tools">
                    {dropdown content=$smarty.capture.tools_list}
                </div>
            </td>
        </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl" div_id="pagination_contents_vendor_holidays"}

</form>

{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="vendor_holidays.update" prefix="top" title=__("add_holiday") hide_tools=true icon="icon-plus"}
{/capture}

{include file="common/mainbox.tpl" 
    title=__("vendor_holidays")
    content=$smarty.capture.mainbox
    adv_buttons=$smarty.capture.adv_buttons
} 