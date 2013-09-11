<?php
class m130625_144651_event_type_OphTrIntravitrealinjection extends CDbMigration
{
	public function up()
	{
		// --- EVENT TYPE ENTRIES ---

		// create an event_type entry for this event type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name'=>'OphTrIntravitrealinjection'))->queryRow()) {
			$group = $this->dbConnection->createCommand()->select('id')->from('event_group')->where('name=:name',array(':name'=>'Treatment events'))->queryRow();
			$this->insert('event_type', array('class_name' => 'OphTrIntravitrealinjection', 'name' => 'Intravitreal injection','event_group_id' => $group['id']));
		}
		// select the event_type id for this event type name
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name'=>'OphTrIntravitrealinjection'))->queryRow();

		// --- ELEMENT TYPE ENTRIES ---

		// create an element_type entry for this element type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name'=>'Site',':eventTypeId'=>$event_type['id']))->queryRow()) {
			$this->insert('element_type', array('name' => 'Site','class_name' => 'Element_OphTrIntravitrealinjection_Site', 'event_type_id' => $event_type['id'], 'display_order' => 1));
		}

		// create an element_type entry for this element type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name'=>'Anaesthetic',':eventTypeId'=>$event_type['id']))->queryRow()) {
			$this->insert('element_type', array('name' => 'Anaesthetic','class_name' => 'Element_OphTrIntravitrealinjection_Anaesthetic', 'event_type_id' => $event_type['id'], 'display_order' => 1));
		}

		// create an element_type entry for this element type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name'=>'Treatment',':eventTypeId'=>$event_type['id']))->queryRow()) {
			$this->insert('element_type', array('name' => 'Treatment','class_name' => 'Element_OphTrIntravitrealinjection_Treatment', 'event_type_id' => $event_type['id'], 'display_order' => 1));
		}

		// create an element_type entry for this element type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name'=>'Anterior Segment',':eventTypeId'=>$event_type['id']))->queryRow()) {
			$this->insert('element_type', array('name' => 'Anterior Segment','class_name' => 'Element_OphTrIntravitrealinjection_AnteriorSegment', 'event_type_id' => $event_type['id'], 'display_order' => 2));
		}

		// create an element_type entry for this element type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name'=>'Post Injection Examination',':eventTypeId'=>$event_type['id']))->queryRow()) {
			$this->insert('element_type', array('name' => 'Post Injection Examination','class_name' => 'Element_OphTrIntravitrealinjection_PostInjectionExamination', 'event_type_id' => $event_type['id'], 'display_order' => 3));
		}
		// create an element_type entry for this element type name if one doesn't already exist
		if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name'=>'Complications',':eventTypeId'=>$event_type['id']))->queryRow()) {
			$this->insert('element_type', array('name' => 'Complications','class_name' => 'Element_OphTrIntravitrealinjection_Complications', 'event_type_id' => $event_type['id'], 'display_order' => 4));
		}

		// get the id for both eyes
		$both_eyes_id = Eye::model()->find("name = 'Both'")->id;

		$this->createTable('ophtrintravitinjection_injectionuser', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'user_id' => 'int(10) unsigned NOT NULL',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_injectionuser_ui_fk` (`user_id`)',
				'CONSTRAINT `ophtrintravitinjection_injectionuser_ui_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');


		$users = $this->dbConnection->createCommand()->selectDistinct('id')->from('user')->where('is_doctor = :doc or is_surgeon = :sur', array(':doc' => true, ':sur' => true));
		foreach ($users->queryAll() as $user) {
			$this->insert('ophtrintravitinjection_injectionuser', array('user_id' => $user['id']));
		}

		// element lookup table ophtrintravitinjection_treatment_drug
		$this->createTable('ophtrintravitinjection_treatment_drug', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'available' => 'boolean NOT NULL DEFAULT True',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_treatment_drug_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_treatment_drug_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_treatment_drug_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_treatment_drug_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Avastin','display_order'=>1));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Eylea','display_order'=>2));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Triamcinolone','display_order'=>3));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Illuvien','display_order'=>4));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Lucentis','display_order'=>5));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Macugen','display_order'=>6));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'Ozurdex','display_order'=>7));
		$this->insert('ophtrintravitinjection_treatment_drug',array('name'=>'PDT','display_order'=>8));
		
		// Site table
		$this->createTable('et_ophtrintravitinjection_site', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'site_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtrintravitinjection_site_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtrintravitinjection_site_cui_fk` (`created_user_id`)',
				'KEY `et_ophtrintravitinjection_site_ev_fk` (`event_id`)',
				'KEY `et_ophtrintravitinjection_site_site_id_fk` (`site_id`)',
				'CONSTRAINT `et_ophtrintravitinjection_site_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_site_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_site_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_site_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		// element lookup table ophtrintravitinjection_anaestheticagent
		$this->createTable('ophtrintravitinjection_anaestheticagent', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'anaesthetic_agent_id' => 'int(10) unsigned NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_anaestheticagent_ti_fk` (`anaesthetic_agent_id`)',
				'CONSTRAINT `ophtrintravitinjection_anaestheticagent_ti_fk` FOREIGN KEY (`anaesthetic_agent_id`) REFERENCES `anaesthetic_agent` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$all = $this->dbConnection->createCommand()->select(array('id', 'name', 'display_order'))->from('anaesthetic_agent')->queryAll();
		foreach ($all as $aa) {
			if ($aa['name'] != 'Hyalase') {
				$this->insert('ophtrintravitinjection_anaestheticagent', array('anaesthetic_agent_id' => $aa['id'], 'display_order' => $aa['display_order']));
			}
		}

		// element lookup table ophtrintravitinjection_anaesthetictype
		$this->createTable('ophtrintravitinjection_anaesthetictype', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'anaesthetic_type_id' => 'int(10) unsigned NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_anaesthetictype_ti_fk` (`anaesthetic_type_id`)',
				'CONSTRAINT `ophtrintravitinjection_anaesthetictype_ti_fk` FOREIGN KEY (`anaesthetic_type_id`) REFERENCES `anaesthetic_type` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$to = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('code=:code',array(':code'=>'Top'))->queryRow();
		$la = $this->dbConnection->createCommand()->select('id')->from('anaesthetic_type')->where('code=:code',array(':code'=>'LA'))->queryRow();

		$this->insert('ophtrintravitinjection_anaesthetictype',array('anaesthetic_type_id'=>$to['id'],'display_order'=>1));
		$this->insert('ophtrintravitinjection_anaesthetictype',array('anaesthetic_type_id'=>$la['id'],'display_order'=>2));

		// element lookup table ophtrintravitinjection_anaestheticdelivery
		$this->createTable('ophtrintravitinjection_anaestheticdelivery', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'anaesthetic_delivery_id' => 'int(10) unsigned NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_anaestheticdelivery_di_fk` (`anaesthetic_delivery_id`)',
				'CONSTRAINT `ophtrintravitinjection_anaestheticdelivery_di_fk` FOREIGN KEY (`anaesthetic_delivery_id`) REFERENCES `anaesthetic_delivery` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$all = $this->dbConnection->createCommand()->select(array('id', 'display_order'))->from('anaesthetic_delivery')->queryAll();
		foreach ($all as $ad) {
			$this->insert('ophtrintravitinjection_anaestheticdelivery', array('anaesthetic_delivery_id' => $ad['id'], 'display_order' => $ad['display_order']));
		}

		// create the table for Anaesthetic
		$this->createTable('et_ophtrintravitinjection_anaesthetic', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'eye_id' => 'int(10) unsigned NOT NULL DEFAULT ' . $both_eyes_id,
				'left_anaesthetictype_id' => 'int(10) unsigned',
				'left_anaestheticdelivery_id' => 'int(10) unsigned',
				'left_anaestheticagent_id' => 'int(10) unsigned',
				'right_anaesthetictype_id' => 'int(10) unsigned',
				'right_anaestheticdelivery_id' => 'int(10) unsigned',
				'right_anaestheticagent_id' => 'int(10) unsigned',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_cui_fk` (`created_user_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_ev_fk` (`event_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_eye_id_fk` (`eye_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_lat_id_fk` (`left_anaesthetictype_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_lad_id_fk` (`left_anaestheticdelivery_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_laa_id_fk` (`left_anaestheticagent_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_rat_id_fk` (`right_anaesthetictype_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_rad_id_fk` (`right_anaestheticdelivery_id`)',
				'KEY `et_ophtrintravitinjection_anaesthetic_raa_id_fk` (`right_anaestheticagent_id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_lat_id_fk` FOREIGN KEY (`left_anaesthetictype_id`) REFERENCES `anaesthetic_type` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_lad_id_fk` FOREIGN KEY (`left_anaestheticdelivery_id`) REFERENCES `anaesthetic_delivery` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_laa_id_fk` FOREIGN KEY (`left_anaestheticagent_id`) REFERENCES `anaesthetic_agent` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_rat_id_fk` FOREIGN KEY (`right_anaesthetictype_id`) REFERENCES `anaesthetic_type` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_rad_id_fk` FOREIGN KEY (`right_anaestheticdelivery_id`) REFERENCES `anaesthetic_delivery` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anaesthetic_raa_id_fk` FOREIGN KEY (`right_anaestheticagent_id`) REFERENCES `anaesthetic_agent` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		// create the table(s) for Anterior Segment
		$this->createTable('ophtrintravitinjection_lens_status', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'default_distance' => 'decimal(2,1) NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_lens_status_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_lens_status_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_lens_status_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_lens_status_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_lens_status',array('name'=>'Phakic','display_order'=>1, 'default_distance' => 4));
		$this->insert('ophtrintravitinjection_lens_status',array('name'=>'Aphakic','display_order'=>2, 'default_distance' => 3.5));
		$this->insert('ophtrintravitinjection_lens_status',array('name'=>'Pseudophakic','display_order'=>3, 'default_distance' => 3.5));

		$this->createTable('et_ophtrintravitinjection_anteriorseg', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'eye_id' => 'int(10) unsigned NOT NULL DEFAULT ' . $both_eyes_id,
				'left_eyedraw' => 'text',
				'right_eyedraw' => 'text',
				'left_lens_status_id' => 'int(10) unsigned',
				'right_lens_status_id' => 'int(10) unsigned',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtrintravitinjection_anteriorseg_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtrintravitinjection_anteriorseg_cui_fk` (`created_user_id`)',
				'KEY `et_ophtrintravitinjection_anteriorseg_ei_fk` (`eye_id`)',
				'KEY `et_ophtrintravitinjection_anteriorseg_llsi_fk` (`left_lens_status_id`)',
				'KEY `et_ophtrintravitinjection_anteriorseg_rlsi_fk` (`right_lens_status_id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anteriorseg_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anteriorseg_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anteriorseg_ei_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anteriorseg_llsi_fk` FOREIGN KEY (`left_lens_status_id`) REFERENCES `ophtrintravitinjection_lens_status` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_anteriorseg_rlsi_fk` FOREIGN KEY (`right_lens_status_id`) REFERENCES `ophtrintravitinjection_lens_status` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		// Treatment lookup tables
		$this->createTable('ophtrintravitinjection_antiseptic_drug', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_antiseptic_drug_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_antiseptic_drug_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_antiseptic_drug_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_antiseptic_drug_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_antiseptic_drug',array('name'=>'Iodine 5%','display_order'=>1));
		$this->insert('ophtrintravitinjection_antiseptic_drug',array('name'=>'Chlorhexidine','display_order'=>2));

		$this->createTable('ophtrintravitinjection_skin_drug', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_skin_drug_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_skin_drug_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_skin_drug_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_skin_drug_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_skin_drug',array('name'=>'Iodine 10%','display_order'=>1));
		$this->insert('ophtrintravitinjection_skin_drug',array('name'=>'Chlorhexidine','display_order'=>2));

		$this->createTable('ophtrintravitinjection_ioplowering', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_ioplowering_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_ioplowering_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_ioplowering_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_ioplowering_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_ioplowering',array('name'=>'Iopidine 0.5%','display_order'=>1));
		$this->insert('ophtrintravitinjection_ioplowering',array('name'=>'Iopidine 1.0%','display_order'=>2));

		// create the table for Treatment
		$this->createTable('et_ophtrintravitinjection_treatment', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'eye_id' => 'int(10) unsigned NOT NULL DEFAULT ' . $both_eyes_id,
				'left_pre_antisept_drug_id' => 'int(10) unsigned',
				'left_pre_skin_drug_id' => 'int(10) unsigned',
				'left_pre_ioplowering_required' => 'boolean',
				'left_pre_ioplowering_id' => 'int(10) unsigned',
				'left_drug_id' => 'int(10) unsigned', // Drug
				'right_drug_id' => 'int(10) unsigned', // Drug
				'left_number' => 'int(10) unsigned', // Number of Injections
				'left_batch_number' => 'varchar(255) DEFAULT \'\'', // Batch Number
				'left_batch_expiry_date' => 'date DEFAULT NULL', // Batch Expiry Date
				'left_injection_given_by_id' => 'int(10) unsigned', // Injection Given By
				'left_injection_time' => 'time',
				'left_post_ioplowering_required' => 'boolean',
				'left_post_ioplowering_id' => 'int(10) unsigned',
				'right_pre_antisept_drug_id' => 'int(10) unsigned',
				'right_pre_skin_drug_id' => 'int(10) unsigned',
				'right_pre_ioplowering_required' => 'boolean',
				'right_pre_ioplowering_id' => 'int(10) unsigned',
				'right_number' => 'int(10) unsigned', // Number of Injections
				'right_batch_number' => 'varchar(255) DEFAULT \'\'', // Batch Number
				'right_batch_expiry_date' => 'date DEFAULT NULL', // Batch Expiry Date
				'right_injection_given_by_id' => 'int(10) unsigned', // Injection Given By
				'right_injection_time' => 'time',
				'right_post_ioplowering_required' => 'boolean',
				'right_post_ioplowering_id' => 'int(10) unsigned',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtrintravitinjection_treatment_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtrintravitinjection_treatment_cui_fk` (`created_user_id`)',
				'KEY `et_ophtrintravitinjection_treatment_ev_fk` (`event_id`)',
				'KEY `et_ophtrintravitinjection_treatment_eye_id_fk` (`eye_id`)',
				'KEY `et_ophtrintravitinjection_treatment_lprad_id_fk` (`left_pre_antisept_drug_id`)',
				'KEY `et_ophtrintravitinjection_treatment_lprsd_id_fk` (`left_pre_skin_drug_id`)',
				'KEY `ophtrintravitinjection_treatment_ldrug_fk` (`left_drug_id`)',
				'KEY `et_ophtrintravitinjection_treatment_linjection_given_by_id_fk` (`left_injection_given_by_id`)',
				'KEY `et_ophtrintravitinjection_treatment_rprad_id_fk` (`right_pre_antisept_drug_id`)',
				'KEY `et_ophtrintravitinjection_treatment_rprsd_id_fk` (`right_pre_skin_drug_id`)',
				'KEY `ophtrintravitinjection_treatment_rdrug_fk` (`right_drug_id`)',
				'KEY `et_ophtrintravitinjection_treatment_rinjection_given_by_id_fk` (`right_injection_given_by_id`)',
				'KEY `et_ophtrintravitinjection_treatment_lpriop_id_fk` (`left_pre_ioplowering_id`)',
				'KEY `et_ophtrintravitinjection_treatment_lpoiop_id_fk` (`left_post_ioplowering_id`)',
				'KEY `et_ophtrintravitinjection_treatment_rpriop_id_fk` (`right_pre_ioplowering_id`)',
				'KEY `et_ophtrintravitinjection_treatment_rpoiop_id_fk` (`right_post_ioplowering_id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_lprad_id_fk` FOREIGN KEY (`left_pre_antisept_drug_id`) REFERENCES `ophtrintravitinjection_antiseptic_drug` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_lprsd_id_fk` FOREIGN KEY (`left_pre_skin_drug_id`) REFERENCES `ophtrintravitinjection_skin_drug` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_treatment_ldrug_fk` FOREIGN KEY (`left_drug_id`) REFERENCES `ophtrintravitinjection_treatment_drug` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_linjection_given_by_id_fk` FOREIGN KEY (`left_injection_given_by_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_rprad_id_fk` FOREIGN KEY (`right_pre_antisept_drug_id`) REFERENCES `ophtrintravitinjection_antiseptic_drug` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_rprsd_id_fk` FOREIGN KEY (`right_pre_skin_drug_id`) REFERENCES `ophtrintravitinjection_skin_drug` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_treatment_rdrug_fk` FOREIGN KEY (`right_drug_id`) REFERENCES `ophtrintravitinjection_treatment_drug` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_rinjection_given_by_id_fk` FOREIGN KEY (`right_injection_given_by_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_lpriop_id_fk` FOREIGN KEY (`left_pre_ioplowering_id`) REFERENCES `ophtrintravitinjection_ioplowering` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_lpoiop_id_fk` FOREIGN KEY (`left_post_ioplowering_id`) REFERENCES `ophtrintravitinjection_ioplowering` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_rpriop_id_fk` FOREIGN KEY (`right_pre_ioplowering_id`) REFERENCES `ophtrintravitinjection_ioplowering` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_treatment_rpoiop_id_fk` FOREIGN KEY (`right_post_ioplowering_id`) REFERENCES `ophtrintravitinjection_ioplowering` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		//Post-injection tables
		$this->createTable('ophtrintravitinjection_postinjection_drops', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_postinjection_drops_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_postinjection_drops_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_postinjection_drops_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_postinjection_drops_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_postinjection_drops',array('name'=>'G. Levofloxacin four times daily for 5 days','display_order'=>1));
		$this->insert('ophtrintravitinjection_postinjection_drops',array('name'=>'G. Chloramphenicol 0.5%  four times daily for 5 days','display_order'=>2));

		// create the table for this element type: Element_OphTrIntravitrealinjection_PostInjectionExamination
		$this->createTable('et_ophtrintravitinjection_postinject', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'eye_id' => 'int(10) unsigned DEFAULT ' . $both_eyes_id,
				'left_finger_count' => 'tinyint(1) unsigned DEFAULT 0',
				'right_finger_count' => 'tinyint(1) unsigned DEFAULT 0',
				'left_iop_check' => 'tinyint(1) unsigned DEFAULT 0',
				'right_iop_check' => 'tinyint(1) unsigned DEFAULT 0',
				'left_drops_id' => 'int(10) unsigned',
				'right_drops_id' => 'int(10) unsigned',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtrintravitinjection_postinject_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtrintravitinjection_postinject_cui_fk` (`created_user_id`)',
				'KEY `et_ophtrintravitinjection_postinject_ev_fk` (`event_id`)',
				'KEY `et_ophtrintravitinjection_postinject_eye_id_fk` (`eye_id`)',
				'KEY `et_ophtrintravitinjection_postinject_ldrops_id_fk` (`left_drops_id`)',
				'KEY `et_ophtrintravitinjection_postinject_rdrops_id_fk` (`right_drops_id`)',
				'CONSTRAINT `et_ophtrintravitinjection_postinject_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_postinject_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_postinject_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_postinject_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_postinject_ldrops_id_fk` FOREIGN KEY (`left_drops_id`) REFERENCES `ophtrintravitinjection_postinjection_drops` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_postinject_rdrops_id_fk` FOREIGN KEY (`right_drops_id`) REFERENCES `ophtrintravitinjection_postinjection_drops` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		// element lookup table
		$this->createTable('ophtrintravitinjection_complicat', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(128) COLLATE utf8_bin NOT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'default' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'description_required' => 'boolean NOT NULL DEFAULT False',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_complicat_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_complicat_cui_fk` (`created_user_id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->insert('ophtrintravitinjection_complicat',array('name'=>'Subconjunctival haemorrhage','display_order'=>1));
		$this->insert('ophtrintravitinjection_complicat',array('name'=>'Conjunctival damage (e.g. tear)','display_order'=>2));
		$this->insert('ophtrintravitinjection_complicat',array('name'=>'Corneal abrasion','display_order'=>3));
		$this->insert('ophtrintravitinjection_complicat',array('name'=>'Lens damage','display_order'=>4));
		$this->insert('ophtrintravitinjection_complicat',array('name'=>'Retinal damage','display_order'=>5));
		$this->insert('ophtrintravitinjection_complicat',array('name'=>'Other','display_order'=>6, 'description_required' => true));

		// create the table for this element type: et_modulename_elementtypename
		$this->createTable('et_ophtrintravitinjection_complications', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
				'eye_id' => 'int(10) unsigned NOT NULL DEFAULT ' . $both_eyes_id,
				'left_oth_descrip' => 'text', // Other Description
				'right_oth_descrip' => 'text', // Other Description
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophtrintravitinjection_complicat_lmui_fk` (`last_modified_user_id`)',
				'KEY `et_ophtrintravitinjection_complicat_cui_fk` (`created_user_id`)',
				'KEY `et_ophtrintravitinjection_complicat_ev_fk` (`event_id`)',
				'KEY `et_ophtrintravitinjection_complicat_eye_id_fk` (`eye_id`)',
				'CONSTRAINT `et_ophtrintravitinjection_complicat_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_complicat_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_complicat_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophtrintravitinjection_complicat_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

		$this->createTable('ophtrintravitinjection_complicat_assignment', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'element_id' => 'int(10) unsigned NOT NULL',
				'eye_id' => 'int(10) unsigned NOT NULL DEFAULT ' . $both_eyes_id,
				'complication_id' => 'int(10) unsigned NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
				'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `ophtrintravitinjection_complicat_assignment_lmui_fk` (`last_modified_user_id`)',
				'KEY `ophtrintravitinjection_complicat_assignment_cui_fk` (`created_user_id`)',
				'KEY `ophtrintravitinjection_complicat_assignment_ele_fk` (`element_id`)',
				'KEY `ophtrintravitinjection_complicat_assign_eye_id_fk` (`eye_id`)',
				'KEY `ophtrintravitinjection_complicat_assignment_lku_fk` (`complication_id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_assignment_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_assignment_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_assignment_ele_fk` FOREIGN KEY (`element_id`) REFERENCES `et_ophtrintravitinjection_complications` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_assign_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`)',
				'CONSTRAINT `ophtrintravitinjection_complicat_assignment_lku_fk` FOREIGN KEY (`complication_id`) REFERENCES `ophtrintravitinjection_complicat` (`id`)',
			), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

	}

	public function down()
	{
		// --- drop any element related tables ---
		// --- drop element tables ---


		$this->dropTable('ophtrintravitinjection_injectionuser');
		$this->dropTable('ophtrintravitinjection_anaestheticagent');
		$this->dropTable('ophtrintravitinjection_anaesthetictype');
		$this->dropTable('ophtrintravitinjection_anaestheticdelivery');
		$this->dropTable('et_ophtrintravitinjection_site');
		$this->dropTable('et_ophtrintravitinjection_anaesthetic');

		$this->dropTable('et_ophtrintravitinjection_treatment');

		$this->dropTable('ophtrintravitinjection_treatment_drug');
		$this->dropTable('ophtrintravitinjection_antiseptic_drug');
		$this->dropTable('ophtrintravitinjection_skin_drug');
		$this->dropTable('ophtrintravitinjection_ioplowering');

		$this->dropTable('et_ophtrintravitinjection_postinject');
		$this->dropTable('ophtrintravitinjection_postinjection_drops');

		$this->dropTable('et_ophtrintravitinjection_anteriorseg');
		$this->dropTable('ophtrintravitinjection_lens_status');

		$this->dropTable('ophtrintravitinjection_complicat_assignment');

		$this->dropTable('et_ophtrintravitinjection_complications');

		$this->dropTable('ophtrintravitinjection_complicat');

		// --- delete event entries ---
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name'=>'OphTrIntravitrealinjection'))->queryRow();

		foreach ($this->dbConnection->createCommand()->select('id')->from('event')->where('event_type_id=:event_type_id', array(':event_type_id'=>$event_type['id']))->queryAll() as $row) {
			$this->delete('audit', 'event_id='.$row['id']);
			$this->delete('event', 'id='.$row['id']);
		}

		// --- delete entries from element_type ---
		$this->delete('element_type', 'event_type_id='.$event_type['id']);

		// --- delete entries from event_type ---
		$this->delete('event_type', 'id='.$event_type['id']);

		// echo "m000000_000001_event_type_OphTrIntravitrealinjection does not support migration down.\n";
		// return false;
		echo "If you are removing this module you may also need to remove references to it in your configuration files\n";
		return true;
	}
}
