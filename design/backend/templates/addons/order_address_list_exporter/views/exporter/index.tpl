<div id="actions_panel" class="actions cm-sticky-scroll" data-ce-padding="37" data-ce-top="41">
    <div class="btn-bar btn-toolbar dropleft pull-right">
        <div class="btn-group dropleft">
            <a class="btn btn-primary cm-submit" data-ca-target-form="export-order-form" data-ca-dispatch="dispatch[exporter.address_list]">Export Address List</a>
            <a class="btn btn-primary cm-submit" data-ca-target-form="export-order-form" data-ca-dispatch="dispatch[exporter.orders]">Export Orders</a>
        </div>
    </div>
</div>

<div class="content content-no-sidebar no-sidebar ufa">
    <div class="content-wrap">
        <form class="form-horizontal for-edit cm-processed-form cm-check-changes" enctype="multipart/form-data" name="export-order-form" method="post" action="{""|fn_url}">
            <div style="display:block;">
                <h4 class="subheader hand" data-target="#order_information" data-toggle="collapse">Export Orders</h4>
                <div id="order_information" class="collapse in">
                    <div class="control-group">
                        <label class="control-label cm-required" for="order_ids">Order Id(s)</label>
                        <div class="controls">
                            <textarea id="order_ids" name="order_ids"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
