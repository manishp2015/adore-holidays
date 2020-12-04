 <div class="wpbs-booking-details-modal-booking-details">

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-left">

        <h3><?php echo __('Booking Data', 'wp-booking-system') ?></h3>

        <form action="#" method="post" class="wpbs-edit-booking-details"  data-type="booking_data">
            <table>
                <?php foreach($this->get_booking_data() as $data): ?>
                    <tr>
                        <td><strong><?php echo $data['label'] ?>:</strong></td>
                        <td class="wpbs-edit-booking-field-<?php echo $data['name'];?> <?php echo ($data['editable']) ? 'wpbs-edit-booking-details-field-editable' : ''; ?>">
                            <span class="wpbs-edit-booking-details-field-view">
                                <p><?php echo $data['value'] ?></p>
                            </span>

                            <?php if($data['editable']): ?>

                                <span class="wpbs-edit-booking-details-field-edit">
                                    <input name="wpbs-edit-booking-field-<?php echo $data['name'];?>" class="wpbs-edit-booking-datepicker" type="text" value="<?php echo wpbs_date_i18n('Y-m-d', $data['time']) ?>">
                                </span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <?php wp_nonce_field( 'wpbs_edit_booking', 'wpbs_edit_booking_data_token', false ); ?>
            <input type="hidden" name="booking_id" value="<?php echo $this->booking->get('id');?>">

            <button class="edit-booking-details-open button button-secondary"><?php echo __('Edit', 'wp-booking-system') ?></button>
            
            <button class="edit-booking-details-save button button-primary"><?php echo __('Save Changes', 'wp-booking-system') ?></button>
            <button class="edit-booking-details-cancel button button-secondary"><?php echo __('Cancel', 'wp-booking-system') ?></button>

            <div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
                <p><?php echo __('Please keep in mind that changing the dates will <strong>not</strong> change the availability in the calendar. You will need to do this manually.', 'wp-booking-system'); ?></p>
            </div>
        
        </form>

    </div>

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-right">
        
        <h3><?php echo __('Form Data', 'wp-booking-system') ?></h3>

        <form action="#" method="post" class="wpbs-edit-booking-details" data-type="booking_details">
            <table>
                <?php foreach($this->get_form_data() as $data): ?>
                    <tr>
                        <td><strong><?php echo $data['label'] ?>:</strong></td>
                        <td class="<?php echo ($data['editable']) ? 'wpbs-edit-booking-details-field-editable' : ''; ?>">
                            <span class="wpbs-edit-booking-details-field-view">
                                <p><?php echo $data['value'] ?></p>
                            </span>

                            <?php if($data['editable']): ?>

                                <span class="wpbs-edit-booking-details-field-edit">

                                    <?php if($data['field']['type'] == 'textarea'): ?>
                                        <textarea name="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>" class="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>"><?php echo strip_tags($data['value']); ?></textarea>
                                    <?php else: ?>
                                        <input name="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>" class="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>" type="text" value="<?php echo $data['value'] ?>">
                                    <?php endif; ?>

                                </span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <?php wp_nonce_field( 'wpbs_edit_booking', 'wpbs_edit_booking_details_token', false ); ?>
            <input type="hidden" name="booking_id" value="<?php echo $this->booking->get('id');?>">

            <button class="edit-booking-details-open button button-secondary"><?php echo __('Edit', 'wp-booking-system') ?></button>
            
            <button class="edit-booking-details-save button button-primary"><?php echo __('Save Changes', 'wp-booking-system') ?></button>
            <button class="edit-booking-details-cancel button button-secondary"><?php echo __('Cancel', 'wp-booking-system') ?></button>

        
        </form>

    </div>

    <div class="wpbs-clear"><!-- --></div>

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-notes">
        
        <h3><?php echo __('Notes', 'wp-booking-system') ?> <?php echo wpbs_get_output_tooltip(__('Notes are only visible to website administrators, they are not sent to the client.', 'wp-booking-system')) ?></h3>

        <div class="wpbs-booking-details-modal-notes-wrap">

            <?php if($this->get_notes()): ?>

                <?php foreach($this->get_notes() as $i => $note): ?>

                    <div class="wpbs-booking-details-modal-note">

                        <p><?php echo nl2br($note['note']); ?></p>

                        <div class="wpbs-booking-details-modal-note-footer">

                            <span class="wpbs-booking-details-modal-note-date-added">
                                <strong><?php echo __('Added on', 'wp-booking-system') ?>:</strong> 
                                <?php echo wpbs_date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $note['timestamp']); ?>
                            </span>
                            
                            <a href="#" data-booking-note="<?php echo $i;?>" data-booking-id="<?php echo $this->booking->get('id');?>" class="wpbs-booking-details-modal-note-remove"><?php echo __('delete note', 'wp-booking-system') ?></a>
                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p class="wpbs-booking-details-modal-note-no-results"><?php echo __('No notes found for this booking.', 'wp-booking-system') ?></p>

            <?php endif; ?>

        </div>
        
        <h3><?php echo __('Add New Note', 'wp-booking-system') ?></h3>

        <form class="wpbs-booking-details-modal-notes-add-new">
        
            <textarea id="wpbs_modal_booking_note" rows="5"></textarea>

            <button class="button button-primary" id="wpbs_modal_add_booking_note" data-booking-id="<?php echo $this->booking->get('id');?>"><?php echo __('Add Note', 'wp-booking-system') ?></button>

        </form>

    </div>
</div>