<div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-left">

    <h3><?php echo __('Payment Information', 'wp-booking-system') ?></h3>

    <table>
        <?php foreach ($payment_information as $data): ?>
            <tr class="wpbs-booking-details-<?php echo sanitize_title($data['label']); ?> wpbs-booking-details-<?php echo sanitize_title($data['label']); ?>-<?php echo sanitize_title($data['value']); ?>">
                <td><strong><?php echo $data['label'] ?>:</strong></td>
                <td><p><?php echo $data['value'] ?></p></td>
            </tr>
        <?php endforeach?>
    </table>

</div>

<div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-right">

    <h3><?php echo __('Order Information', 'wp-booking-system') ?></h3>

    <table>
        <?php foreach ($order_information as $data): ?>
            <tr class="wpbs-booking-details-<?php echo sanitize_title($data['label']); ?> wpbs-booking-details-<?php echo sanitize_title($data['label']); ?>-<?php echo sanitize_title($data['value']); ?>">
                <td><strong><?php echo $data['label'] ?>:</strong></td>
                <td><p><?php echo $data['value'] ?></p></td>
            </tr>
        <?php endforeach?>
    </table>
</div>