<?php
	/**
	 * phpGroupWare - property: a Facilities Management System.
	 *
	 * @author Sigurd Nes <sigurdne@online.no>
	 * @copyright Copyright (C) 2016 Free Software Foundation, Inc. http://www.fsf.org/
	 * This file is part of phpGroupWare.
	 *
	 * phpGroupWare is free software; you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation; either version 2 of the License, or
	 * (at your option) any later version.
	 *
	 * phpGroupWare is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with phpGroupWare; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	 *
	 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
	 * @internal Development of this application was funded by http://www.bergen.kommune.no/bbb_/ekstern/
	 * @package property
	 * @subpackage helpdesk
	 * @version $Id$
	 */
	/**
	 * Description
	 * @package property
	 */
	/**
	 * Description of lag_agresso_varemottak
	 *
	 * @author Sigurd Nes
	 */
	if (isset($data['order_id']) && $data['order_id'] && isset($data['varemottak']) && $data['varemottak'])
	{
		$exporter_varemottak = new lag_agresso_varemottak($this->acl_location, $id);
		$exporter_varemottak->transfer($id);
	}

	class lag_agresso_varemottak
	{

		private $acl_location;
		private $values;

		function __construct( $acl_location, $id )
		{
			switch ($acl_location)
			{
				case '.ticket':
					$this->acl_location = $acl_location;
					$this->values = ExecMethod('property.sotts.read_single', $id);
					break;
				default:
					$this->acl_location = '.project.workorder';
					$this->values = ExecMethod('property.soworkorder.read_single', $id);
					break;
			}
		}

		public function transfer( $id )
		{
			$values = $this->values;
//		_debug_array($values);die();

			$param = array(
				'order_id' => $values['order_id'],
				'lines' => array(
					array(
						'UnitCode' => 'STK',
						'Quantity' => 1,
					)
				)
			);

			$exporter_varemotta = new BkBygg_exporter_varemottak_til_Agresso();
			$exporter_varemotta->create_transfer_xml();
			$exporter_varemotta->output();
			die();
			$export_ok = $exporter_ordre->transfer();
			if ($export_ok)
			{
				$this->log_transfer( $id );
			}
		}

		private function log_transfer( $id )
		{
			$id = (int)$id;
			switch ($this->acl_location)
			{
				case '.ticket':
					$historylog = CreateObject('property.historylog', 'tts');
					$table = 'fm_tts_tickets';
					break;
				default:
					$historylog = CreateObject('property.historylog', 'workorder');
					$table = 'fm_workorder';
					break;
			}
			$historylog->add('RM', $id, "Varemottak overført til agresso");
			$GLOBALS['phpgw']->db->query("UPDATE {$table} SET order_received = 1 WHERE id = {$id}");
		}
	}

	class BkBygg_exporter_varemottak_til_Agresso extends BkBygg_exporter_data_til_Agresso
	{

		var $transfer_xml;
		var $connection;
		var $order_id;

		public function __construct( $param )
		{
			parent::__construct($param);
		}

		public function create_transfer_xml( $param )
		{
			$Orders = array();
			$Detail = array();
			$i = 1;
			foreach ($param['lines'] as $line)
			{
				$Detail[] = array(
					'LineNo' => $i,
					'Status' => 'N',
					'UnitCode' => $line['UnitCode'],
					'Quantity' => $line['Quantity'],
				);
				$i++;
			}

			$Orders['Order'][] = array(
				'OrderNo' => $param['order_id'],
				'VoucherType' => 'VV',
				'TransType' => 51,
				'Details' => array('Detail' => $Detail)
			);

//			_debug_array($Orders);
//			die();

			$root_attributes = array(
				'Version' => "542",
				'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
				'xsi:noNamespaceSchemaLocation' => "http://services.agresso.com/schema/ABWOrder/2004/07/02/ABWOrder.xsd"
			);
			$xml_creator = new xml_creator('ABWOrder', $root_attributes);
			$xml_creator->fromArray($Orders);
			$this->transfer_xml = $xml_creator->getDocument();
			return $this->transfer_xml;
			//		$xml_creator->output();
			//		die();
		}

		protected function create_file_name( $ref = '' )
		{
			if (!$ref)
			{
				throw new Exception('BkBygg_exporter_data_til_Agresso::create_file_name() Mangler referanse');
			}
			$fil_katalog = $this->config->config_data['export']['path'];

			$filename = "{$fil_katalog}/FDV_varemottak_{$ref}.xml";

			//Sjekk om filen eksisterer
			if (file_exists($filename))
			{
				unlink($filename);
			}

			return $filename;
		}
	}