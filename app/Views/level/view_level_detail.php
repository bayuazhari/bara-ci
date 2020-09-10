					<div class="table-responsive">
						<h4 align="center"><?= @$level->level_name ?></h4>
						<table class="table table-striped table-bordered table-td-valign-middle">
							<thead>
								<tr>
									<th width="1%">#</th>
									<th class="text-nowrap">Menu</th>
									<th class="text-nowrap">Create</th>
									<th class="text-nowrap">Read</th>
									<th class="text-nowrap">Update</th>
									<th class="text-nowrap">Delete</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if(@$menu) :
									$no=0;
									foreach ($menu as $mn) :
										$level_role = $setting->getLevelByRole(@$level->level_id, $mn->menu_id);
										$level_create = @$level_role->create;
										$level_read = @$level_role->read;
										$level_update = @$level_role->update;
										$level_delete = @$level_role->delete;
										$level_data = @$level_role->data;

										if($level_create == 1){
											$create = "<i class='fa fa-check' style='color: green;'></i>";
										}else{
											$create = "<i class='fa fa-times' style='color: red;'></i>";
										}

										if($level_read == 1){
											$read = "<i class='fa fa-check' style='color: green;'></i>";
										}else{
											$read = "<i class='fa fa-times' style='color: red;'></i>";
										}

										if($level_update == 1){
											$update = "<i class='fa fa-check' style='color: green;'></i>";
										}else{
											$update = "<i class='fa fa-times' style='color: red;'></i>";
										}

										if($level_delete == 1){
											$delete = "<i class='fa fa-check' style='color: green;'></i>";
										}else{
											$delete = "<i class='fa fa-times' style='color: red;'></i>";
										}
										$no++;

										echo "<tr><td>".$no."</td>
										<td>".$mn->menu_name."</td>
										<td>".$create."</td>
										<td>".$read."</td>
										<td>".$update."</td>
										<td>".$delete."</td></tr>";
									endforeach;
								endif;
							?>
								<tr>
									<td></td>
									<td><strong>Default Menu</strong></td>
									<td colspan="4"><?= @$level->menu_name ?></td>
                            	</tr>
								<tr>
									<td></td>
									<td><strong>Status</strong></td>
									<?php
										if(@$level->level_status == '1'){
											echo '<td class="text-success" colspan="4">Active</td>';
										}elseif(@$level->level_status == '0'){
											echo '<td class="text-danger" colspan="4">Inactive</td>';
										}else{
											echo '';
										}
									?>
                            	</tr>
							</tbody>
						</table>
					</div>