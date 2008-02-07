<?php
	/**
	 * property - Menus
	 *
	 * @author Dave Hall <skwashd@phpgroupware.org>
	 * @author Sigurd Nes <sigurdne@online.no>
	 * @copyright Copyright (C) 2007 Free Software Foundation, Inc. http://www.fsf.org/
	 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
	 * @package property
	 * @version $Id$
	 */

	/*
	   This program is free software: you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation, either version 3 of the License, or
	   (at your option) any later version.

	   This program is distributed in the hope that it will be useful,
	   but WITHOUT ANY WARRANTY; without even the implied warranty of
	   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	   GNU General Public License for more details.

	   You should have received a copy of the GNU General Public License
	   along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 */


	/**
	 * Menus
	 *
	 * @package property
	 */
	class property_menu
	{
		/**
		 * Get the menus for the property
		 *
		 * @return array available menus for the current user
		 */
		public function get_menu()
		{
			$acl = CreateObject('phpgwapi.acl');
			$menus = array();

			$entity			= CreateObject('property.soadmin_entity');
			$entity_list 	= $entity->read(array('allrows' => true));

			$start_page = 'location';
			if ( isset($GLOBALS['phpgw_info']['user']['preferences']['property']['default_start_page'])
					&& $GLOBALS['phpgw_info']['user']['preferences']['property']['default_start_page'] )
			{
					$start_page = $GLOBALS['phpgw_info']['user']['preferences']['property']['default_start_page'];
			}

			$menus['navbar'] = array
			(
				'property' => array
				(
					'text'	=> $GLOBALS['phpgw']->translation->translate('property', array(), 'property'),
					'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => "property.ui{$start_page}.index") ),
					'image'	=> array('property', 'navbar'),
					'order'	=> 35,
					'group'	=> 'facilities management'
				),
			);

			$menus['toolbar'] = array();

			$soadmin_location	= CreateObject('property.soadmin_location');
			$locations	= $soadmin_location->select_location_type();

			if ( isset($GLOBALS['phpgw_info']['user']['apps']['admin']) )
			{
				if ( is_array($entity_list) && count($entity_list) )
				{
					foreach($entity_list as $entry)
					{
						$admin_children_entity["entity_{$entry['id']}"] = array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_entity.category', 'entity_id'=> $entry['id'])),
							'text'	=> $entry['name']
						);
			
						$cat_list = $entity->read_category(array('allrows'=>True,'entity_id'=>$entry['id']));

						foreach($cat_list as $category)
						{
							$admin_children_entity["entity_{$entry['id']}"]['children']["entity_{$entry['id']}_{$category['id']}"]	= array
							(
								'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_entity.list_attribute', 'entity_id'=> $entry['id'] , 'cat_id'=> $category['id'])),
								'text'	=> $category['name']
							);
						}
					}
				}

				$admin_children_tenant = array
				(
					'tenant_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'tenant', 'menu_selection' => 'admin::property::tenant::tenant_cats') )
					),
					'tenant_global_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant Global Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.uicategories.index', 'appname' => 'fm_tenant', 'global_cats' => 'True', 'menu_selection' => 'admin::property::tenant::tenant_global_cats') )
					),
					'tenant_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.tenant', 'menu_selection' => 'admin::property::tenant::tenant_attribs') )
					),
					'claims_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant Claim Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'tenant_claim', 'menu_selection' => 'admin::property::tenant::claims_cats') )
					)
				);

				$admin_children_vendor = array
				(
					'vendor_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Vendor Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'vendor', 'menu_selection' => 'admin::property::vendor::vendor_cats') )
					),
					'vendor_global_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Vendor Global Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.uicategories.index', 'appname' => 'fm_vendor', 'global_cats' => 'True', 'menu_selection' => 'admin::property::vendor::vendor_global_cats') )
					),
					'vendor_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Vendor Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' => '.vendor', 'menu_selection' => 'admin::property::vendor::vendor_attribs') )
					)		
				);
				$admin_children_owner = array
				(
					'owner_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Owner Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'owner', 'menu_selection' => 'admin::property::owner::owner_cats') )
					),
					'owner_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Owner Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.owner', 'menu_selection' => 'admin::property::owner::owner_attribs') )
					)
				);

				$admin_children_accounting = array
				(
					'accounting_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'b_account', 'menu_selection' => 'admin::property::accounting::accounting_cats') )
					),
					'accounting_dim_b'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting dim b', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'dim_b', 'menu_selection' => 'admin::property::accounting::accounting_dim_b') )
					),
					'accounting_dim_d'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting dim d', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'dim_d', 'menu_selection' => 'admin::property::accounting::accounting_dim_d') )
					),
					'accounting_tax'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting tax', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'tax', 'menu_selection' => 'admin::property::accounting::accounting_tax') )
					),
					'voucher_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting voucher category', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'voucher_cat', 'menu_selection' => 'admin::property::accounting::voucher_cats') )
					),
					'voucher_type'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting voucher type', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'voucher_type', 'menu_selection' => 'admin::property::accounting::voucher_type') )
					)
				);

				$admin_children_agreement = array
				(
					'agreement_status'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Agreement status', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'agreement_status', 'menu_selection' => 'admin::property::agreement::agreement_status') )
					),
					'agreement_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Agreement Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.agreement', 'menu_selection' => 'admin::property::agreement::agreement_attribs') )
					),
					'service_agree_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('service agreement categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 's_agreement', 'menu_selection' => 'admin::property::agreement::service_agree_cats') )
					),
					'service_agree_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('service agreement Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.s_agreement', 'menu_selection' => 'admin::property::agreement::service_agree_attribs') )
					),
					'service_agree_item_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('service agreement item Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.s_agreement.detail', 'menu_selection' => 'admin::property::agreement::service_agree_item_attribs') )
					),
					'rental_agree_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('rental agreement categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'r_agreement', 'menu_selection' => 'admin::property::agreement::rental_agree_cats') )
					),
					'rental_agree_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('rental agreement Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.r_agreement', 'menu_selection' => 'admin::property::agreement::rental_agree_attribs') )
					),
					'rental_agree_item_attribs'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('rental agreement item Attributes', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_attribute', 'appname' => 'property', 'location' =>'.r_agreement.detail', 'menu_selection' => 'admin::property::agreement::rental_agree_item_attribs') )
					),

				);

				foreach ( $locations as $location )
				{
					$admin_children_location_children["attribute_loc_{$location['id']}"] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_location.list_attribute', 'type_id' => $location['id'])),
						'text'	=> $location['name'] . ' ' . $GLOBALS['phpgw']->translation->translate('attributes', array(), 'property'),
					);
					$admin_children_location_children["category_{$location['id']}"] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uicategory.index', 'type' => 'location', 'type_id' => $location['id'], 'menu_selection' => "admin::property::location::location::category_{$location['id']}") ),
						'text'	=> $location['name'] . ' ' . $GLOBALS['phpgw']->translation->translate('categories', array(), 'property'),
					);	
				}

				$admin_children_location = array
				(
					'street'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Street', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'street', 'menu_selection' => 'admin::property::location::street') )
					),
					'district'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('District', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'district', 'menu_selection' => 'admin::property::location::district') )
					),
					'town'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Part of town', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uip_of_town.index') )
					),
					'location' => array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_location.index') ),
						'text'	=> $GLOBALS['phpgw']->translation->translate('Location type', array(), 'property'),
						'children'	=> $admin_children_location_children
					),
					'config' => array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_location.config') ),
						'text'	=> $GLOBALS['phpgw']->translation->translate('Config')
					)
				);


				$menus['admin'] = array
				(
					'index'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Configuration', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.uiconfig.index', 'appname' => 'property') )
					),
					'entity'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Admin entity', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiadmin_entity.index') ),
						'children' => $admin_children_entity
					),
					'location'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Admin Location', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiadmin_location.index') ),
						'children' => $admin_children_location
					),
					'inactive_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Update the not active category for locations', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uilocation.update_cat') )
					),
					'request_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Request Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'request', 'menu_selection' => 'admin::property::request_cats') )
					),
					'workorder_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Workorder Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'wo', 'menu_selection' => 'admin::property::workorder_cats') )
					),
					'workorder_detail'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Workorder Detail Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'wo_hours', 'menu_selection' => 'admin::property::workorder_detail') )
					),
					'ticket_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Ticket Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'ticket', 'menu_selection' => 'admin::property::ticket_cats') )
					),
					'tenant'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiactor.index', 'role' => 'tenant', 'admin' => true) ),
						'children'	=> $admin_children_tenant
					),
					'owner'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Owner', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiactor.index', 'role' => 'owner', 'admin' => true) ),
						'children'	=> $admin_children_owner
					),
					'vendor'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Vendor', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiactor.index', 'role' => 'vendor', 'admin' => true) ),
						'children'	=> $admin_children_vendor
					),
					'doc_cats'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Document Categories', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'document', 'menu_selection' => 'admin::property::doc_cats') )
					),
					'building_part'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Building Part', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'building_part') )
					),
					'tender'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Tender chapter', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'tender_chapter', 'menu_selection' => 'admin::property::tender') )
					),
					'id_control'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('ID Control', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiadmin.edit_id') )
					),
					'permissions'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Permissions', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiadmin.list_acl') )
					),
					'user_contact'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('User contact info', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiadmin.contact_info') )
					),
					'request_status'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Request status', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'request_status') )
					),
					'request_condition'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Request condition_type', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uicategory.index', 'type' => 'r_condition_type', 'menu_selection' => 'admin::property::request_condition') )
					),
					'workorder_status'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Workorders status', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'workorder_status') )
					),
					'agreement'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Agreement', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'agreement_status') ),
						'children'	=> $admin_children_agreement
					),
					'document_status'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Document Status', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'document_status', 'menu_selection' => 'admin::property::document_status') )
					),
					'unit'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Unit', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_2.index', 'type' => 'unit', 'menu_selection' => 'admin::property::unit') )
					),
					'key_location'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Key location', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_3.index', 'type' => 'key_location') )
					),
					'branch'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Branch', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uistandard_3.index', 'type' => 'branch') )
					),
					'accounting'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Accounting', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uib_account.index') ),
						'children'	=> $admin_children_accounting
					),
					'admin_async'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Admin Async services', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uialarm.index') )
					),
					'async'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Async services', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiasync.index') )
					),
					'list_functions'	=> array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Admin custom functions', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'admin.ui_custom.list_custom_function','appname' => 'property') )
					),
				);
			}

			if ( isset($GLOBALS['phpgw_info']['user']['apps']['preferences']) )
			{
				$menus['preferences'] = array
				(
					array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Preferences', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/preferences/preferences.php', array('appname' => 'property', 'type'=> 'user') )
					),
					array
					(
						'text'	=> $GLOBALS['phpgw']->translation->translate('Grant Access', array(), 'property'),
						'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction' => 'property.uiadmin.aclprefs', 'acl_app'=> 'property'))
					)
				);

				$menus['toolbar'][] = array
				(
					'text'	=> $GLOBALS['phpgw']->translation->translate('Preferences', array(), 'property'),
					'url'	=> $GLOBALS['phpgw']->link('/preferences/preferences.php', array('appname'	=> 'property')),
					'image'	=> array('property', 'preferences')
				);
			}

			$menus['navigation'] = array();
			if ( $acl->check('.location', PHPGW_ACL_READ, 'property') )
			{
				$children = array();

		//		$soadmin_location	= CreateObject('property.soadmin_location');
		//		$locations	= $soadmin_location->select_location_type();
				foreach ( $locations as $location )
				{
					$children["loc_{$location['id']}"] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uilocation.index', 'type_id' => $location['id'])),
						'text'	=> $location['name']
					);
				}

				$children['tenant'] = array
				(
					'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uilocation.index', 'lookup_tenant' => 1, 'type_id' => $soadmin_location->read_config_single('tenant_id'))),
					'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant', array(), 'property')
				);
				$children['gabnr'] = array
				(
					'url'	=>	$GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uigab.index')),
					'text'	=> $GLOBALS['phpgw']->translation->translate('gabnr', array(), 'property')
				);
				$children['summary'] = array
				(
					'url'	=>	$GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uilocation.summary')),
					'text'	=>	$GLOBALS['phpgw']->translation->translate('Summary', array(), 'property')
				);

/*				if ( $acl->check('.location',16) )
				{
					$children['type'] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_location.index')),
						'text'	=> $GLOBALS['phpgw']->translation->translate('Location type')
					);
					$children['config'] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiadmin_location.config')),
						'text'	=> $GLOBALS['phpgw']->translation->translate('Config')
					);
				}
*/
				$menus['navigation']['location'] = array
				(
					'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uilocation.index', 'type_id'=>1)),
					'text'	=> $GLOBALS['phpgw']->translation->translate('Location', array(), 'property'),
					'image'	=> array('property', 'location'),
					'children'	=> $children
				);
			}

			if ( $acl->check('.ifc', PHPGW_ACL_READ, 'property') )
			{
				$menus['navigation']['ifc'] = array
				(
					'url'		=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiifc.import')),
					'text'		=> $GLOBALS['phpgw']->translation->translate('IFC', array(), 'property'),
					'image'		=> array('property', 'ifc'),
					'children'	=> array
					(
						'import'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiifc.import')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('import', array(), 'property')
						)
					)
				);
			}

			if ( $acl->check('.ticket',PHPGW_ACL_READ, 'property') )
			{
				$menus['navigation']['helpdesk'] = array
				(
					'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uitts.index')),
					'text'	=> $GLOBALS['phpgw']->translation->translate('Helpdesk', array(), 'property')
				);
			}

			if ( $acl->check('.project', PHPGW_ACL_READ, 'property') )
			{
				$menus['navigation']['project'] = array
				(
					'url'		=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiproject.index')),
					'text'		=> $GLOBALS['phpgw']->translation->translate('Project', array(), 'property'),
					'children'	=> array
					(
						'project'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiproject.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Project', array(), 'property')
						),
						'workorder'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiworkorder.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Workorder', array(), 'property')
						),
						'request'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uirequest.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Request', array(), 'property')
						),
						'template'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uitemplate.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('template', array(), 'property')
						),
						'claim'		=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uitenant_claim.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant claim', array(), 'property')
						)
					)
				);
			}

			if ( $acl->check('.invoice', PHPGW_ACL_READ, 'property') )
			{
				$children = array();
				if ( $acl->check('.invoice', PHPGW_ACL_PRIVATE, 'property') )
				{
					$children['investment'] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiinvestment.index')),
						'text'	=>	$GLOBALS['phpgw']->translation->translate('Investment value', array(), 'property')
					);

					$children['import'] = array
					(
						'url'	=>	$GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiXport.import')),
						'text'	=> $GLOBALS['phpgw']->translation->translate('Import invoice', array(), 'property')
					);

					$children['export'] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiXport.export')),
						'text'	=>	$GLOBALS['phpgw']->translation->translate('Export invoice', array(), 'property')
					);
				}

				if ( $acl->check('.invoice', PHPGW_ACL_ADD, 'property') )
				{
					$children['add'] = array
					(
						'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiinvoice.add')),
						'text'	=>	$GLOBALS['phpgw']->translation->translate('Add', array(), 'property')
					);
				}

				$menus['navigation']['invoice'] = array
				(
					'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiinvoice.index')),
					'text'	=> $GLOBALS['phpgw']->translation->translate('Invoice', array(), 'property'),
					'image'	=> array('property', 'invoice'),
					'children'	=> array_merge(array
					(
						'paid'		=> array
						(
							'url'	=>	$GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiinvoice.index', 'paid'=>true)),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Paid', array(), 'property')
						),
						// Should this be process? skwashd jan08
						'consume'	=> array
						(
							'url'	=>	$GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiinvoice.consume')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('consume', array(), 'property')
						),
						'budget'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uib_account.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Budget account', array(), 'property')
						),
						'vendor'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiactor.index', 'role'=> 'vendor')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Vendor', array(), 'property')
						),
						'tenant'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiactor.index', 'role'=> 'tenant')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Tenant', array(), 'property')
						)
					), $children)
				);
			}

			if ( $acl->check('.budget', PHPGW_ACL_READ, 'property') )
			{
				$menus['navigation']['budget'] = array
				(
					'url'		=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uibudget.index')),
					'text'		=> $GLOBALS['phpgw']->translation->translate('Budget', array(), 'property'),
					'children'	=> array
					(
						'basis'		=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uibudget.basis')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('basis', array(), 'property')
						),
						'budget'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uibudget.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('budget', array(), 'property')
						),
						'obligations'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uibudget.obligations')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('obligations', array(), 'property')
						)
					)
				);
			}

			if ( $acl->check('.agreement', PHPGW_ACL_READ, 'property') )
			{
				$admin_menu = array();
				if ( $acl->check('.agreement',16) )
				{
					$admin_menu = array
					(
						'group'		=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uipricebook.agreement_group')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Agreement group', array(), 'property')
						),
						'activities'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uipricebook.activity')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Activities', array(), 'property')
						),
						'agreement'		=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiagreement.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Agreement', array(), 'property')
						)
					);
				}

				$menus['navigation']['agreement'] = array
				(
					'url'		=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiagreement.index')),
					'text'		=> $GLOBALS['phpgw']->translation->translate('Agreement', array(), 'property'),
					'children'	=> array
					(
						'pricebook'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uiagreement.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Pricebook', array(), 'property'),
							'children'	=> $admin_menu
						),
						'service'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uis_agreement.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Service', array(), 'property')
						),
						'rental'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uir_agreement.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('Rental', array(), 'property')
						),
						'alarm'		=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uialarm.list_alarm')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('alarm', array(), 'property')
						)
					)
				);
			}

			if ( $acl->check('.document', PHPGW_ACL_READ, 'property') )
			{
				$menus['navigation']['documentation'] = array
				(
					'url'		=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uidocument.index')),
					'text'		=> $GLOBALS['phpgw']->translation->translate('Documentation', array(), 'property'),
					'children'	=> array
					(
						'location'	=> array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uidocument.index')),
							'text'	=> $GLOBALS['phpgw']->translation->translate('location', array(), 'property')
						)
					)
				);
				if (is_array($entity_list) && count($entity_list) )
				{
					foreach ( $entity_list as $entry )
					{
						if($entry['documentation'])
						{
							$menus['navigation']['documentation']['children']["entity_{$entry['id']}"] = array
							(
								'url'	=> $GLOBALS['phpgw']->link('/index.php', array('menuaction'=> 'property.uidocument.index', 'entity_id' => $entry['id'])),
								'text'	=> $entry['name']
							);
						}
					}
				}
			}

			if ( $acl->check('.custom', PHPGW_ACL_READ, 'property') )
			{
				$menus['navigation']['custom'] = array
				(
					'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uicustom.index')),
					'text'	=> $GLOBALS['phpgw']->translation->translate('Custom', array(), 'property'),
				);
			}

			if ( is_array($entity_list) && count($entity_list) )
			{
				foreach($entity_list as $entry)
				{
					if ( $acl->check(".entity.{$entry['id']}", PHPGW_ACL_READ, 'property') )
					{
						$menus['navigation']["entity_{$entry['id']}"] = array
						(
							'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uientity.index', 'entity_id'=> $entry['id'])),
							'text'	=> $entry['name']
						);
					}
					
					$cat_list = $entity->read_category(array('allrows'=>True,'entity_id'=>$entry['id']));

					foreach($cat_list as $category)
					{
						if ( $acl->check(".entity.{$entry['id']}.{$category['id']}", PHPGW_ACL_READ, 'property') )
						{
							$menus['navigation']["entity_{$entry['id']}"]['children']["entity_{$entry['id']}_{$category['id']}"]	= array
							(
								'url'	=> $GLOBALS['phpgw']->link('/index.php',array('menuaction'=> 'property.uientity.index', 'entity_id'=> $entry['id'] , 'cat_id'=> $category['id'])),
								'text'	=> $category['name']
							);
						}
					}
				}
			}
			unset($entity_list);
			unset($entity);
			return $menus;
		}
	}
