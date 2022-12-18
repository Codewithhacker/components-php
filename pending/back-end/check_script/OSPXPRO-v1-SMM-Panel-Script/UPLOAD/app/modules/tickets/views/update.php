<style>
  .ticket-contents .item .avatar.supporter-icon {
    height: 40px;
  }
</style>
<section class="<?=(isset($module))? $module : ''?> p-t-20">   
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <h3 class="h4"><i class="fa fa-ticket"></i> <?=lang("Ticket_no")?><?=$ticket->id?></h3>
        </div>
        <div class="card-body">
          <div class="ticket-details">
            <table class="table">
              <tbody>
                <tr>
                  <td scope="row"><?=lang("Status")?></td>
                  <td>
                    <?php
                      $button_type = "info";
                      if (!empty($ticket->status)) {
                        switch ($ticket->status) {
                          case 'pending':
                            $button_type = "primary";
                            break;
                          case 'closed':
                            $button_type = "dark";
                            break;
                          case 'new':
                            $button_type = "info";
                            break;
                        }
                      }
                    ?>
                    <div class="btn-group">
                      <?php 
                        if (get_role("admin") || get_role('supporter')) {
                      ?>
                      <div class="dropdown">
                        <button  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn round btn-<?=$button_type?> dropdown-toggle btn-sm">
                          <span class="p-r-5 p-l-5"><?=ticket_status_title($ticket->status)?> </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right has-shadow">

                          <a href="javascript:void(0)" data-url="<?=cn($module."/ajax_change_status/".$ticket->ids)?>" data-status="closed" class="ajaxChangeStatus dropdown-item"><?=lang("submit_as_closed")?></a>
                          <a href="javascript:void(0)" data-url="<?=cn($module."/ajax_change_status/".$ticket->ids)?>" data-status="pending" class="ajaxChangeStatus dropdown-item"><?=lang("submit_as_pending")?></a>
                          <a href="javascript:void(0)" data-url="<?=cn($module."/ajax_change_status/".$ticket->ids)?>" data-status="new" class="ajaxChangeStatus dropdown-item"><?=lang("submit_as_new")?></a>
                        </div>

                      </div>
                      <?php }else{?>
                        <span class="btn round btn-<?=$button_type?> btn-sm"><?=ticket_status_title($ticket->status)?>
                        </span>
                      <?php }?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td scope="row"><?=lang("Name")?></td>
                  <td><?=(!empty($ticket->first_name)) ? $ticket->first_name. " ".$ticket->last_name: ""?></td>
                </tr>

                <tr>
                  <td scope="row"><?=lang("Email")?></td>
                  <td><?=(!empty($ticket->user_email)) ? $ticket->user_email: ""?></td>
                </tr>

                <tr>
                  <td scope="row"><?=lang("Created")?></td>
                  <td><?=(!empty($ticket->created)) ? convert_timezone($ticket->created, 'user'): "" ?></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <h3 class="h4"></i> <?=$ticket->subject?></h3>
        </div>
        <div class="card-body">
          <div class="ticket-contents">
            <?php
              $short_name_user = '<i class="fe fe-user"></i>';
              if (!empty($ticket->first_name)) {
                $last_name_user = $ticket->last_name;
                $first_name_user = $ticket->first_name;
                $short_name_user = $first_name_user[0].$last_name_user[0];
              }
            ?>
            <div class="item p-l-5 d-flex">
              <div class="media-left pr-1">
                <span class="avatar avatar-md">
                    <span class="media-object rounded-circle text-circle "><?=$short_name_user?></span>
                </span>
              </div>
              <div class="content">
                <div class="username">
                  <?=(!empty($ticket->first_name)) ? $ticket->first_name. " ".$ticket->last_name: ""?>
                  <span class="text-muted time"><?=(!empty($ticket->created)) ? convert_timezone($ticket->created, 'user'): "" ?></span>
                </div>
                <div class="message p-t-10">
                  <?=(!empty($ticket->description)) ? $ticket->description: "" ?>
                </div>
              </div>
            </div>
            
            <?php
              if (!empty($ticket_content)) {
                foreach ($ticket_content as $key => $row) {
                  $short_name = "U";
                  if (!empty($row->first_name)) {
                    $last_name = $row->last_name;
                    $first_name = $row->first_name;
                    $short_name = $first_name[0].$last_name[0];
                  }
            ?>
            <div class="item p-l-5 d-flex">
              <div class="media-left pr-1">
                <?php
                  if ($row->role != 'supporter') {
                ?>
                <span class="avatar avatar-md">
                    <span class="media-object rounded-circle text-uppercase text-circle <?=(!empty($row->role) && $row->role=='admin') ? 'admin': "" ?>"><?=$short_name?></span>
                </span>
                <?php }else{?>
                <span class="avatar supporter-icon" style="background-image: url(<?=BASE.'assets/images/support.svg'?>)"></span>
               <?php }?>
              </div>
              <div class="content">
                <div class="username">
                  <?=(!empty($row->first_name) ? $row->first_name. " ".$row->last_name: "")?>
                  <span class="text-muted time"><?=(!empty($row->created)) ? convert_timezone($row->created, 'user'): "" ?></span>
                </div>
                <div class="message p-t-10">
                  <?=(!empty($row->message)) ? $row->message: "" ?>
                </div>
              </div>
            </div>
            <?php }}?>
          </div>
          <?php
            if (get_role("admin") || get_role("supporter") || $ticket->status == 'pending' || $ticket->status == 'new') {
          ?>
          <form class="form actionForm m-t-20" action="<?=cn($module."/ajax_update/".$ticket->ids)?>" data-redirect="<?=cn("$module/view/".$ticket->id)?>" method="POST">
            <div class="form-group">
              <label for="userinput8"><?=lang("Message")?></label>
              <textarea rows="5" class="form-control square plugin_editor" name="message" ></textarea>
            </div>
            <button type="submit" class="btn round btn-primary btn-min-width mr-1 mb-1"><?=lang("Submit")?></button>
            </br></br>
            <div class="fs-13"> <strong class="text-danger">Note: </strong> 
                  If you have any related image then please <a href="https://imgbb.com/" class="text-danger" rel="nofollow" target="_blank"> <strong>Click here</strong></a> to upload it on the site and give us the embed code in a message box to solve your issue 
                  </div>
            
            
            
            
          </form>
          
          <?php }?>
        </div>
      </div>
    </div>
  </div>
  
</section>


<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 200});
  });
</script>





 <?php
        if (get_role("admin")) {
      ?>
      
<div class="row" id="result_ajaxSearch">

    <div class="col-md-12 col-xl-12 tr_58a30f4cac11b9c27332fb1bfb47f597">
    <div class="card card-collapsed">
      <div class="card-header">
        <h3 class="card-title" data-toggle="card-collapse">
          <span class="bg-question"><i class="fa fa-question-circle" aria-hidden="true"></i></span>
         SHORT REPLAYS </h3>
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>
      <div class="card-body">
        <p>
          <b>[ FUND REP. ]</b></br></br>
          <b>Hello </br>
          Fund Added</br>
          
          Thanks and Regards</br>
          Support Team.</b>
          
             </br></br>
       
    
    
        
        <b>[ PAYMENT REP. ]</b></br></br>
          <b>Hello </br>
         Send Payment Order ID Or ScreenShort
         
         </br>
          
          Thanks and Regards</br>
          Support Team.</b>
          
            
            </br></br>
        
        <b>[ SPEED UP REP. ]</b></br></br>
          <b>Hello </br>
          Your Services Is Now Added To Speed Up</br>
          
          Thanks and Regards</br>
          Support Team.</b>
          
            
            </br></br>
       
        
        <b>[ REFILL UP REP. ]</b></br></br>
          <b>Hello </br>
          Your Services Is Now Added To REFILL</br>
          
          Thanks and Regards</br>
          Support Team.</b>
          
          
            
            
            
        </p></div>
    </div>
  </div>
      <?php }else{ ?>
<?php } ?>