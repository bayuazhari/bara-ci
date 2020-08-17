					<div class="table-responsive">
						<table class="table table-striped table-bordered table-td-valign-middle">
							<tr class="text-center">
								<td colspan="2"><h1 class="flag-icon flag-icon-<?= strtolower(@$country->country_alpha2_code) ?>"></h1></td>
							</tr>
							<tr>
								<td><strong>Alpha-2 Code</strong></td>
								<td><?= @$country->country_alpha2_code ?></td>
							</tr>
							<tr>
								<td><strong>Alpha-3 Code</strong></td>
								<td><?= @$country->country_alpha3_code ?></td>
							</tr>
							<tr>
								<td><strong>Numeric Code</strong></td>
								<td><?= @$country->country_numeric_code ?></td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td><?= @$country->country_name ?></td>
							</tr>
							<tr>
								<td><strong>Capital</strong></td>
								<td><?= @$country->country_capital ?></td>
							</tr>
							<tr>
								<td><strong>Demonym</strong></td>
								<td><?= @$country->country_demonym ?></td>
							</tr>
							<tr>
								<td><strong>Total Area</strong></td>
								<td>
								<?php
									if(@$country->country_area){
										echo number_format(@$country->country_area).' km<sup>2</sup>';
									}
								?>
								</td>
							</tr>
							<tr>
								<td><strong>Total Population</strong></td>
								<td>
								<?php
								if(@$population->total_population){
									echo number_format($population->total_population).' People <strong>('.$population->population_year.')</strong>';
								}
								?>
								</td>
							</tr>
							<tr>
								<td><strong>IDD Code</strong></td>
								<td>
								<?php
									if(@$country->idd_code){
										echo '+'.$country->idd_code;
									}
								?>
								</td>
							</tr>
							<tr>
								<td><strong>ccTLD</strong></td>
								<td>
								<?php
									if(@$country->cctld){
										echo '.'.$country->cctld;
									}
								?>
								</td>
							</tr>
							<tr>
								<td><strong>Currency</strong></td>
								<td>
								<?php
									if(@$country->currency_id){
										echo $country->currency_code.' - '.$country->currency_name;
									}
								?>
								</td>
							</tr>
							<tr>
								<td><strong>Language</strong></td>
								<td>
								<?php
									if(@$country->lang_id){
										echo $country->lang_code.' - '.$country->lang_name;
									}
								?>
								</td>
							</tr>
							<tr>
								<td><strong>Status</strong></td>
								<?php
									if(@$country->country_status == 1){
										echo '<td class="text-success">Active</td>';
									}elseif(@$country->country_status == 0){
										echo '<td class="text-danger">Inactive</td>';
									}else{
										echo '<td></td>';
									}
	                            ?>
	                        </tr>
						</table>
					</div>