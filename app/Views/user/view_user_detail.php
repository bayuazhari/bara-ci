					<div class="table-responsive">
						<table class="table table-striped table-bordered table-td-valign-middle">
						<?php
							if(@$user->user_photo_name){
								$user_photo = $user->user_id.'/'.$user->user_photo_name;
							}else{
								$user_photo = 'user-0.png';
							}
						?>
							<tr>
								<td class="text-center" colspan="2"><img src="<?php echo base_url('assets/img/user/'.$user_photo); ?>" height="150" alt=""></td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td><?= @$user->first_name.' '.@$user->last_name ?></td>
							</tr>
						<?php
							if($user->email_verification == 1){
								$email_verification = ' <i class="fa fa-check-circle text-success" title="Verified"></i>';
							}elseif($user->email_verification == 0){
								$email_verification = ' <i class="fa fa-exclamation-triangle text-warning" title="Not Verified"></i>';
							}else{
								$email_verification = '';
							}

							if($user->phone_verification == 1){
								$phone_verification = ' <i class="fa fa-check-circle text-success" title="Verified"></i>';
							}elseif($user->phone_verification == 0){
								$phone_verification = ' <i class="fa fa-exclamation-triangle text-warning" title="Not Verified"></i>';
							}else{
								$phone_verification = '';
							}
						?>
							<tr>
								<td><strong>Email</strong></td>
								<td><?= @$user->user_email.$email_verification ?></td>
							</tr>
							<tr>
								<td><strong>Phone</strong></td>
								<td><?= @'+'.$user->country_calling_code.$user->user_phone.$phone_verification ?></td>
							</tr>
							<tr>
								<td><strong>Address</strong></td>
								<td><?= @$user->user_address.', '.@$user->sdistrict_name.', '.@$user->district_name.', '.@$user->city_name.', '.@$user->state_name.', '.@$user->country_name.' '.@$user->zip_code ?></td>
							</tr>
							<tr>
								<td><strong>Level</strong></td>
								<td><?= @$user->level_name ?></td>
							</tr>
							<tr>
								<td><strong>Registration Date</strong></td>
								<td><?= date('F d, Y H:i:s', strtotime(@$user->registration_date)) ?></td>
							</tr>
							<tr>
								<td><strong>Request Reset Password</strong></td>
								<?php
									if(@$user->req_reset_pass == 1){
										echo '<td class="text-primary">True</td>';
									}elseif(@$user->req_reset_pass == 0){
										echo '<td class="text-danger">False</td>';
									}else{
										echo '<td></td>';
									}
								?>
							</tr>
							<tr>
								<td><strong>Status</strong></td>
								<?php
									if(@$user->user_status == 1){
										echo '<td class="text-success">Active</td>';
									}elseif(@$user->user_status == 0){
										echo '<td class="text-danger">Blocked</td>';
									}else{
										echo '<td></td>';
									}
								?>
							</tr>
						</table>
					</div>