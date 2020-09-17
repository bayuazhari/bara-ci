					<div class="table-responsive">
						<table class="table table-striped table-bordered table-td-valign-middle">
							<tr>
								<td><strong>Name</strong></td>
								<td><?= @$user->first_name.' '.@$user->last_name ?></td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td><?= @$user->user_email ?></td>
							</tr>
							<tr>
								<td><strong>Phone</strong></td>
								<td><?= @'+'.$user->country_calling_code.$user->user_phone ?></td>
							</tr>
							<tr>
								<td><strong>Address</strong></td>
								<td><?= @$user->user_address.', '.@$user->sdistrict_name.', '.@$user->district_name.', '.@$user->city_name.', '.@$user->state_name.', '.@$user->country_name ?></td>
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
										echo '<td class="text-success">Active</td>';
									}elseif(@$user->req_reset_pass == 0){
										echo '<td class="text-danger">Inactive</td>';
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
										echo '<td class="text-danger">Inactive</td>';
									}else{
										echo '<td></td>';
									}
								?>
							</tr>
						</table>
					</div>