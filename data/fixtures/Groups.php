<?php
/**
 * Groups fixture.
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Group {
	/**
	 * @return array
	 */
	static function _get() {
		$g = [];
		$g[] = [
			'id' =>  Uuid::get('group.id.sales'),
			'name' => 'Sales',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.it_support'),
			'name' => 'IT support',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.management'),
			'name' => 'Management',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.human_resource'),
			'name' => 'Human resource',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.creative'),
			'name' => 'Creative',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.operations'),
			'name' => 'Operations',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.accounting'),
			'name' => 'Accounting',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.leadership_team'),
			'name' => 'Leadership team',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.developer'),
			'name' => 'Developer',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.quality_assurance'),
			'name' => 'Quality assurance',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.traffic'),
			'name' => 'Traffic',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.freelancer'),
			'name' => 'Freelancer',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.ergonom'),
			'name' => 'Ergonom',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.board'),
			'name' => 'Board',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.marketing'),
			'name' => 'Marketing',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.resource_planning'),
			'name' => 'Resource planning',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.procurement'),
			'name' => 'Procurement',
		];
		$g[] = [
			'id' =>  Uuid::get('group.id.network'),
			'name' => 'Network',
		];

		return $g;
	}
}