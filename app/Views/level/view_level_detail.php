					<div class="table-responsive">
						<h4 align="center"><?= @$level->level_name ?></h4>
						<table class="table table-striped table-bordered table-td-valign-middle">
							<thead>
								<tr>
									<th width="1%">#</th>
									<th class="text-nowrap">Menu</th>
									<th class="text-nowrap">Read</th>
									<th class="text-nowrap">Create</th>
									<th class="text-nowrap">Update</th>
									<th class="text-nowrap">Delete</th>
									<th class="text-nowrap">Data</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if(@$menu) :
									$group_no=0;
									$no=0;
									foreach ($menu as $key => $mn) :
										if(@$menu[$key-1]->mgroup_id != $mn->mgroup_id){
											$group_no++;
											$no=0;

											echo "<tr><td><strong>".$group_no."</strong></td><td colspan='6'><strong>".$mn->mgroup_name."</strong></td></tr>";
										}

										$level_role = $setting->getLevelByRole(@$level->level_id, $mn->menu_id);
										$level_read = @$level_role->read;
										$level_create = @$level_role->create;
										$level_update = @$level_role->update;
										$level_delete = @$level_role->delete;
										$level_data = @$level_role->data;

										if($level_read == 1){
											$read = "<i class='fa fa-check' style='color: green;'></i>";
										}else{
											$read = "<i class='fa fa-times' style='color: red;'></i>";
										}

										if($level_create == 1){
											$create = "<i class='fa fa-check' style='color: green;'></i>";
										}else{
											$create = "<i class='fa fa-times' style='color: red;'></i>";
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

										if($level_data == 'DT01'){
											$data = "All";
										}elseif($level_data == 'DT02'){
											$data = "Own";
										}else{
											$data = "-";
										}
										
										$no++;

										echo "<tr><td>".$group_no.".".$no."</td>
										<td>".$mn->menu_name."</td>
										<td>".$read."</td>
										<td>".$create."</td>
										<td>".$update."</td>
										<td>".$delete."</td>
										<td>".$data."</td></tr>";
									endforeach;
								endif;
							?>
								<tr>
									<td></td>
									<td><strong>Default Menu</strong></td>
									<td colspan="5"><?= @$level->menu_name ?></td>
                            	</tr>
								<tr>
									<td></td>
									<td><strong>Status</strong></td>
									<?php
										if(@$level->level_status == '1'){
											echo '<td class="text-success" colspan="5">Active</td>';
										}elseif(@$level->level_status == '0'){
											echo '<td class="text-danger" colspan="5">Inactive</td>';
										}else{
											echo '';
										}
									?>
                            	</tr>
							</tbody>
						</table>
					</div>