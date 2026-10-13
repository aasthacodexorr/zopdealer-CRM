<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  
                  <!-- Simple Detail : START -->
                  <tr>
                    <td align="left">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0">
                          <th colspan="2" class="p-50 table-primary" ><?php echo $activity_type; ?> Details</th>
                        </tr>
                        <?php if(!empty($title)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;" class="p-50">
                          <td class="p-50" style="text-transform:uppercase;">Title</td>
                          <td class="p-50" ><?php echo $title; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($start_date)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;" class="p-50">
                          <td class="p-50" style="text-transform:uppercase;">Start Date</td>
                          <td class="p-50" ><?php echo $start_date; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($end_date) && $activity_type == "Appointment"){ ?>
                        <tr style="border-bottom: 1px solid #ccc;" class="p-50">
                          <td class="p-50" style="text-transform:uppercase;">End Date</td>
                          <td class="p-50" ><?php echo $end_date; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($module)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;" class="p-50">
                          <td class="p-50" style="text-transform:uppercase;">Related To</td>
                          <td class="p-50" ><?php echo $module; ?> <?php echo $module_identifier; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($attendee_names)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;" class="p-50">
                          <td class="p-50" style="text-transform:uppercase;">Attendee(s)</td>
                          <td class="p-50" ><?php echo $attendee_names; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($description)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;" class="p-50">
                          <td class="p-50" style="text-transform:uppercase;">Description</td>
                          <td class="p-50" ><?php echo $description; ?></td>
                        </tr>
                        <?php } ?>

                      </table>
                    </td>
                  </tr>
                  <!-- Simple Detail : END -->

                </table>