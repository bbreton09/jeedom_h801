<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class h801 extends eqLogic {
  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'h801_dep';
    $cmd = "dpkg -l | grep sendip";
    exec($cmd, $output, $return_var);
    if ($output[0] != "") {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    $cmd = 'sudo apt-get -y install sendip arping arp-scan >> ' . log::getPathToLog('h801_dep') . ' 2>&1 &';
    exec($cmd);
  }

  public function preUpdate() {
    if ($this->getConfiguration('addr') == '') {
      throw new Exception(__('L\adresse ne peut etre vide',__FILE__));
    }
  }

  public function preSave() {
    $this->setLogicalId($this->getConfiguration('addr'));
    if ($this->getConfiguration('color') == '') {
      $this->setConfiguration('color', '000000');
    }
    if ($this->getConfiguration('w1') == '') {
      $this->setConfiguration('w1', '00');
    }
    if ($this->getConfiguration('w2') == '') {
      $this->setConfiguration('w2', '00');
    }
    if ($this->getConfiguration('w3') == '') {
      $this->setConfiguration('w3', '00');
    }
    if ($this->getConfiguration('w4') == '') {
      $this->setConfiguration('w4', '00');
    }
    if ($this->getConfiguration('w5') == '') {
      $this->setConfiguration('w5', '00');
    }
    exec('ping -c1 ' . $this->getConfiguration('addr'));
    $cline = "LANG=C ip neigh | grep " . $this->getConfiguration('addr') . " | awk '{print $4}'";
    //$cline = "LANG=C arp -an | grep " . $this->getConfiguration('addr') . " | awk '{print $4}'";
    //log::add('h801', 'debug', $cline);
    exec($cline, $output, $return_var);
    //log::add('h801', 'debug', $output[0]);
    //Ajout de isset
    if (strpos(isset($output[0]),':') !== false) {
      $mac = explode(":", $output[0]);
      $this->setConfiguration('mac', $mac[5].$mac[4].$mac[3]);
    }
    if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] != '') {
      $this->setConfiguration('ip', $_SERVER['SERVER_ADDR']);
    }
  }

  public function postSave() {
    $h801Cmd = $this->getCmd(null, 'w1');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de l info white 1');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Etat Blanc 1', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('w1');
      $h801Cmd->setConfiguration('label', 'w1');
      $h801Cmd->setConfiguration('value', '00');
      $h801Cmd->setType('info');
      $h801Cmd->setSubType('string');
      $h801Cmd->setIsVisible(0);
      $h801Cmd->setDisplay('generic_type','LIGHT_STATE');
      $h801Cmd->save();
    }
    $w1Id = $h801Cmd->getId();
    $h801Cmd = $this->getCmd(null, 'w2');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de l info white 2');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Etat Blanc 2', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('w2');
      $h801Cmd->setConfiguration('label', 'w2');
      $h801Cmd->setConfiguration('value', '00');
      $h801Cmd->setType('info');
      $h801Cmd->setSubType('string');
      $h801Cmd->setIsVisible(0);
      $h801Cmd->setDisplay('generic_type','LIGHT_STATE');
      $h801Cmd->save();
    }
      $w2Id = $h801Cmd->getId();
    $h801Cmd = $this->getCmd(null, 'w1action');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande white 1');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Blanc 1', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setValue($w1Id);
      $h801Cmd->setLogicalId('w1action');
      $h801Cmd->setConfiguration('label', 'w1action');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('slider');
      $h801Cmd->setDisplay('generic_type','LIGHT_SLIDER');
      $h801Cmd->save();
    }
    $h801Cmd = $this->getCmd(null, 'w2action');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande white 2');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Blanc 2', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setValue($w2Id);
      $h801Cmd->setLogicalId('w2action');
      $h801Cmd->setConfiguration('label', 'w2action');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('slider');
      $h801Cmd->setDisplay('generic_type','LIGHT_SLIDER');
      $h801Cmd->save();
    }
    $h801Cmd = $this->getCmd(null, 'complete');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande complete');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Complet', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('complete');
      $h801Cmd->setConfiguration('label', 'complete');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('message');
      $h801Cmd->setIsVisible('0');
      $h801Cmd->setDisplay('generic_type','DONT');
      $h801Cmd->save();
    }


    if ($this->getConfiguration('mode') == 'rgb') {
    $h801Cmd = $this->getCmd(null, 'color');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de l info color');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Etat Couleur', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('color');
      $h801Cmd->setConfiguration('label', 'color');
      $h801Cmd->setConfiguration('value', '#000000');
      $h801Cmd->setType('info');
      $h801Cmd->setSubType('string');
      $h801Cmd->setIsVisible(0);
      $h801Cmd->setDisplay('generic_type','LIGHT_COLOR');
      $h801Cmd->save();
    }
    $colorId = $h801Cmd->getId();
    $h801Cmd = $this->getCmd(null, 'coloraction');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande color');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Couleur', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setValue($colorId);
      $h801Cmd->setLogicalId('coloraction');
      $h801Cmd->setConfiguration('label', 'coloraction');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('color');
      $h801Cmd->setDisplay('generic_type','LIGHT_SETCOLOR');
      $h801Cmd->save();
    }
    $h801Cmd = $this->getCmd(null, 'effect');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande effect');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Effet', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('effect');
      $h801Cmd->setConfiguration('label', 'effect');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('message');
      $h801Cmd->setIsVisible('0');
      $h801Cmd->setDisplay('generic_type','DONT');
      $h801Cmd->save();
    }
    $h801Cmd = $this->getCmd(null, 'w3');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'w3action');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'w4');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'w4action');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'w5');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'w5action');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
  } else {
    $h801Cmd = $this->getCmd(null, 'color');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'coloraction');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'effect');
    if (is_object($h801Cmd)) {
      $h801Cmd->remove();
    }
    $h801Cmd = $this->getCmd(null, 'w3');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de l info white 3');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Etat Blanc 3', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('w3');
      $h801Cmd->setConfiguration('label', 'w3');
      $h801Cmd->setConfiguration('value', '00');
      $h801Cmd->setType('info');
      $h801Cmd->setSubType('string');
      $h801Cmd->setIsVisible(0);
      $h801Cmd->setDisplay('generic_type','LIGHT_STATE');
      $h801Cmd->save();
    }
    $w3Id = $h801Cmd->getId();
    $h801Cmd = $this->getCmd(null, 'w4');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de l info white 4');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Etat Blanc 4', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('w4');
      $h801Cmd->setConfiguration('label', 'w4');
      $h801Cmd->setConfiguration('value', '00');
      $h801Cmd->setType('info');
      $h801Cmd->setSubType('string');
      $h801Cmd->setIsVisible(0);
      $h801Cmd->setDisplay('generic_type','LIGHT_STATE');
      $h801Cmd->save();
    }
    $w4Id = $h801Cmd->getId();
    $h801Cmd = $this->getCmd(null, 'w5');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de l info white 5');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Etat Blanc 5', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setLogicalId('w5');
      $h801Cmd->setConfiguration('label', 'w5');
      $h801Cmd->setConfiguration('value', '00');
      $h801Cmd->setType('info');
      $h801Cmd->setSubType('string');
      $h801Cmd->setIsVisible(0);
      $h801Cmd->setDisplay('generic_type','LIGHT_STATE');
      $h801Cmd->save();
    }
    $w5Id = $h801Cmd->getId();
    $h801Cmd = $this->getCmd(null, 'w3action');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande white 3');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Blanc 3', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setValue($w3Id);
      $h801Cmd->setLogicalId('w3action');
      $h801Cmd->setConfiguration('label', 'w3action');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('slider');
      $h801Cmd->setDisplay('generic_type','LIGHT_SLIDER');
      $h801Cmd->save();
    }
    $h801Cmd = $this->getCmd(null, 'w4action');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande white 4');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Blanc 4', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setValue($w4Id);
      $h801Cmd->setLogicalId('w4action');
      $h801Cmd->setConfiguration('label', 'w4action');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('slider');
      $h801Cmd->setDisplay('generic_type','LIGHT_SLIDER');
      $h801Cmd->save();
    }
    $h801Cmd = $this->getCmd(null, 'w5action');
    if (!is_object($h801Cmd)) {
      log::add('h801', 'debug', 'Création de la commande white 5');
      $cmds = $this->getCmd();
      $order = count($cmds);
      $h801Cmd = new h801Cmd();
      $h801Cmd->setName(__('Blanc 5', __FILE__));
      $h801Cmd->setEqLogic_id($this->id);
      $h801Cmd->setEqType('h801');
      $h801Cmd->setOrder($order);
      $h801Cmd->setValue($w5Id);
      $h801Cmd->setLogicalId('w5action');
      $h801Cmd->setConfiguration('label', 'w5action');
      $h801Cmd->setType('action');
      $h801Cmd->setSubType('slider');
      $h801Cmd->setDisplay('generic_type','LIGHT_SLIDER');
      $h801Cmd->save();
    }
  }



  }

  public function sendColor( $addr, $mac, $ip, $color ) {
    $cmd = "sudo sendip -p ipv4 -is " . $ip . " -p udp -us 30978 -ud 30977 -d 0xfbeb" . $color . $mac . "00 -v " . $addr;
    log::add('h801', 'info', 'Commande recue : ' . $cmd);
    exec($cmd);
    $h801 = self::byLogicalId($addr, 'h801');
    if ($h801->getConfiguration('mode') == 'rgb') {
      $h801->setConfiguration('color', '#' . substr($color,0,6));
      $h801->setConfiguration('w1', substr($color,6,2));
      $h801->setConfiguration('w2', substr($color,8,2));
    } else {
      $h801->setConfiguration('w1', substr($color,0,2));
      $h801->setConfiguration('w2', substr($color,2,2));
      $h801->setConfiguration('w3', substr($color,4,2));
      $h801->setConfiguration('w4', substr($color,6,2));
      $h801->setConfiguration('w5', substr($color,8,2));
    }
    $h801->save();
    if ($h801->getConfiguration('mode') == 'rgb') {
      $h801Cmd = $h801->getCmd(null, 'color');
      $h801Cmd->setConfiguration('value', '#' . substr($color,0,6));
      $h801Cmd->event('#' . substr($color,0,6));
      $h801Cmd->save();
    } else {
      $h801Cmd = $h801->getCmd(null, 'w3');
      $h801Cmd->setConfiguration('value', substr($color,4,2));
      $h801Cmd->event(substr($color,4,2));
      $h801Cmd->save();
      $h801Cmd = $h801->getCmd(null, 'w4');
      $h801Cmd->setConfiguration('value', substr($color,6,2));
      $h801Cmd->event(substr($color,6,2));
      $h801Cmd->save();
      $h801Cmd = $h801->getCmd(null, 'w5');
      $h801Cmd->setConfiguration('value', substr($color,8,2));
      $h801Cmd->event(substr($color,8,2));
      $h801Cmd->save();
    }
    $h801Cmd = $h801->getCmd(null, 'w1');
    $h801Cmd->setConfiguration('value', substr($color,0,2));
    $h801Cmd->event(substr($color,6,2));
    $h801Cmd->save();
    $h801Cmd = $h801->getCmd(null, 'w2');
    $h801Cmd->setConfiguration('value', substr($color,2,2));
    $h801Cmd->event(substr($color,8,2));
    $h801Cmd->save();
  }

  public function sendEffect( $addr, $mac, $ip, $effect ) {
      $cmd = "sudo sendip -p ipv4 -is " . $ip . " -p udp -us 30978 -ud 30977 -d 0xfbec" . $effect . "01000000" . $mac . "00 -v " . $addr;
      log::add('h801', 'info', 'Commande recue : ' . $cmd);
      exec($cmd);
  }

}

class h801Cmd extends cmd {
  public function preSave() {
		if ($this->getSubtype() == 'message') {
			$this->setDisplay('title_disable', 1);
		}
	}

  public function execute($_options = null) {
    switch ($this->getType()) {
      case 'info' :
        return $this->getConfiguration('value');
        break;

      case 'action' :
        $h801 = $this->getEqLogic();
        $mac = $h801->getConfiguration('mac');
        $addr = $h801->getConfiguration('addr');
        $ip = $h801->getConfiguration('ip');
        switch ($this->getSubType()) {
          case 'slider':
          $value = $_options['slider'];
          if ($value == '100') {
            $value = 'ff';
          }
          $lenght = strlen($value);
          if ($lenght == '1') {
            $value = '0' . $value;
          }
          if ($h801->getConfiguration('mode') == 'rgb') {
            if ($this->getConfiguration('label') == "w1action") {
              $color = str_replace('#','',$h801->getConfiguration('color')) . $value . $h801->getConfiguration('w2');
            } else {
              $color = str_replace('#','',$h801->getConfiguration('color')) . $h801->getConfiguration('w1') . $value;
            }
          } else {
            if ($this->getConfiguration('label') == "w1action") {
              $color = $value . $h801->getConfiguration('w2') . $h801->getConfiguration('w3') . $h801->getConfiguration('w4') . $h801->getConfiguration('w5');
            } else if ($this->getConfiguration('label') == "w2action") {
              $color = $h801->getConfiguration('w1') . $value . $h801->getConfiguration('w3') . $h801->getConfiguration('w4') . $h801->getConfiguration('w5');
            } else if ($this->getConfiguration('label') == "w3action") {
              $color = $h801->getConfiguration('w1') . $h801->getConfiguration('w2') . $value . $h801->getConfiguration('w4') . $h801->getConfiguration('w5');
            } else if ($this->getConfiguration('label') == "w4action") {
              $color = $h801->getConfiguration('w1') . $h801->getConfiguration('w2') . $h801->getConfiguration('w3') . $value . $h801->getConfiguration('w5');
            } else {
              $color = $h801->getConfiguration('w1') . $h801->getConfiguration('w2') . $h801->getConfiguration('w3') . $h801->getConfiguration('w4') . $value;
            }
          }
          break;

          case 'color':
          $value = str_replace('#','',$_options['color']);
          if (ctype_xdigit($value) && strlen($value) == 6) {
            $color = $value . $h801->getConfiguration('w1') . $h801->getConfiguration('w2');
          } else {
            log::add('h801', 'info', 'Couleur recue non conforme : ' . $_options['color']);
            return false;
          }
          break;

          case 'message':
          $color = $_options['message'];
          break;
        }
        if ($this->getConfiguration('label') == 'effect') {
          h801::sendEffect($addr, $mac, $ip, $color);
          $value = $color;
        } else {
          h801::sendColor($addr, $mac, $ip, $color);
        }

        if ($this->getSubType() == 'color') {
          $value = '#' . $value;
          log::add('h801', 'info', $value);
          return $value;
        }
	if ($this->getSubType() == 'slider' && $value == 'ff') {
          $value = 100;
        }
        $this->setConfiguration('value', $value);
        $this->save();
        return $value;

    }

  }
}

?>
