<div class="table-responsive">
    <h1><img src="<?= base_url('assets/imgs/orders.png') ?>" class="header-img" style="margin-top:-2px;"> Orders</h1>
    <hr>
    <?php if (!empty($orders)) { ?>
        <div style="margin-bottom:10px;">
            <select class="selectpicker changeOrder">
                <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'id' ? 'selected' : '' ?> value="id">Order by new</option>
                <option <?= (isset($_GET['order_by']) && $_GET['order_by'] == 'processed') || !isset($_GET['order_by']) ? 'selected' : '' ?> value="processed">Order by not processed</option>
            </select>
        </div>
        <table class="table table-condensed table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Preview</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orders as $tr) {
                    if ($tr['processed'] == 0) {
                        $class = 'bg-danger';
                        $type = 'No processed';
                    }
                    if ($tr['processed'] == 1) {
                        $class = 'bg-success';
                        $type = 'Processed';
                    }
                    if ($tr['processed'] == 2) {
                        $class = 'bg-warning';
                        $type = 'Rejected';
                    }
                    ?>
                    <tr>
                        <td id="order_id-id-<?= $tr['order_id'] ?>"># <?= $tr['order_id'] ?></td>
                        <td><?= date('d.M.Y / H:m:s', $tr['date']); ?></td>
                        <td>
                            <i class="fa fa-user" aria-hidden="true"></i> 
                            <?= $tr['first_name'] . ' ' . $tr['last_name'] ?>
                        </td>
                        <td><i class="fa fa-phone" aria-hidden="true"></i> <?= $tr['phone'] ?></td>
                        <td class="<?= $class ?> text-center" data-action-id="<?= $tr['id'] ?>">
                            <?php
                            ?>
                            <div class="status" style="padding:5px; font-size:16px;">
                                -- <b><?= $type ?></b> --
                            </div>
                            <div style="margin-bottom:4px;"><a href="javascript:void(0);" onclick="changeStatus(<?= $tr['id'] ?>, 1)" class="btn btn-success btn-xs">Processed</a></div>
                            <div style="margin-bottom:4px;"><a href="javascript:void(0);" onclick="changeStatus(<?= $tr['id'] ?>, 0)" class="btn btn-danger btn-xs">No processed</a></div>
                            <div style="margin-bottom:4px;"><a href="javascript:void(0);" onclick="changeStatus(<?= $tr['id'] ?>, 2)" class="btn btn-warning btn-xs">Rejected</a></div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0);" class="btn btn-default more-info" data-toggle="modal" data-target="#modalPreviewMoreInfo" style="margin-top:10%;" data-more-info="<?= $tr['order_id'] ?>">
                                More Info 
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="hidden" id="order-id-<?= $tr['order_id'] ?>">
                            <table class="table more-info-purchase">
                                <tbody>
                                    <tr>
                                        <td><b>Email</b></td>
                                        <td><a href="mailto:<?= $tr['email'] ?>"><?= $tr['email'] ?></a></td>
                                    </tr>
                                    <tr>
                                        <td><b>City</b></td>
                                        <td><?= $tr['city'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Address</b></td>
                                        <td><?= $tr['address'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Postcode</b></td>
                                        <td><?= $tr['post_code'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Notes</b></td>
                                        <td><?= $tr['notes'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Come from site</b></td>
                                        <td><?= $tr['referrer'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Payment Type</b></td>
                                        <td><?= $tr['payment_type'] ?></td>
                                    </tr>
                                    <?php if ($tr['payment_type'] == 'PayPal') { ?>
                                        <tr>
                                            <td><b>PayPal Status</b></td>
                                            <td><?= $tr['paypal_status'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><b>Products</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            $arr_products = unserialize($tr['products']);
                                            foreach ($arr_products as $product_id => $product_quantity) {
                                                $productInfo = modules::run('admin/admin/getProductInfo', $product_id);
                                                ?>
                                                <div>
                                                    <div class="pull-left">
                                                        <img src="<?= base_url('attachments/shop_images/' . $productInfo['image']) ?>" alt="Product" style="width:100px; margin-right:10px;" class="img-responsive">
                                                    </div>
                                                    <a data-toggle="tooltip" data-placement="top" title="Click to preview" target="_blank" href="<?= base_url($productInfo['url']) ?>">
                                                        <?= base_url($productInfo['url']) ?>
                                                        <div><b>Quantity:</b> <?= $product_quantity ?></div>
                                                    </a>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <hr>
                                            <?php }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?= $links_pagination ?>
    <?php } else { ?>
        <div class="alert alert-info">No orders to the moment!</div>
    <?php } ?>
</div>
<hr>
<h3>Paypal Account Settings</h3>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Paypal sandbox mode</div>
            <div class="panel-body">
                <?php if ($this->session->flashdata('paypal_sandbox')) { ?>
                    <div class="alert alert-info"><?= $this->session->flashdata('paypal_sandbox') ?></div>
                <?php } ?>
                <form method="POST" action="">
                    <div class="input-group">
                        <input class="form-control" name="paypal_sandbox" value="<?= $paypal_sandbox ?>" type="text">
                        <span class="input-group-btn">
                            <button class="btn btn-default" value="" type="submit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Paypal business email</div>
            <div class="panel-body">
                <?php if ($this->session->flashdata('paypal_email')) { ?>
                    <div class="alert alert-info"><?= $this->session->flashdata('paypal_email') ?></div>
                <?php } ?>
                <form method="POST" action="">
                    <div class="input-group">
                        <input class="form-control" name="paypal_email" value="<?= $paypal_email ?>" type="text">
                        <span class="input-group-btn">
                            <button class="btn btn-default" value="" type="submit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Paypal currency (make sure is supported from paypal!)</div>
            <div class="panel-body">
                <?php if ($this->session->flashdata('paypal_currency')) { ?>
                    <div class="alert alert-info"><?= $this->session->flashdata('paypal_currency') ?></div>
                <?php } ?>
                <form method="POST" action="">
                    <div class="input-group">
                        <input class="form-control" name="paypal_currency" value="<?= $paypal_currency ?>" type="text">
                        <span class="input-group-btn">
                            <button class="btn btn-default" value="" type="submit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalPreviewMoreInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Preview <b id="client-name"></b></h4>
            </div>
            <div class="modal-body" id="preview-info-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function changeStatus(id, to_status) {
        $.post("<?= base_url('changeOrderStatus') ?>", {the_id: id, to_status: to_status}, function (data) {
            if (data == '1') {
                if (to_status == 0) {
                    $('[data-action-id="' + id + '"] div.status b').text('No processed');
                    $('[data-action-id="' + id + '"]').removeClass().addClass('bg-danger text-center');
                }
                if (to_status == 1) {
                    $('[data-action-id="' + id + '"] div.status b').text('Processed');
                    $('[data-action-id="' + id + '"]').removeClass().addClass('bg-success  text-center');
                }
                if (to_status == 2) {
                    $('[data-action-id="' + id + '"] div.status b').text('Rejected');
                    $('[data-action-id="' + id + '"]').removeClass().addClass('bg-warning  text-center');
                }
            }
        });
    }
    $(".changeOrder").change(function () {
        window.location.href = '<?= base_url('admin/orders') ?>?order_by=' + $(this).val();
    });
    $(document).ready(function () {
        $('.more-info').click(function () {
            $('#preview-info-body').empty();
            var order_id = $(this).data('more-info');
            var text = $('#order_id-id-' + order_id).text();
            $("#client-name").empty().append(text);
            var html = $('#order-id-' + order_id).html();
            $("#preview-info-body").append(html);
        });
    });
</script>