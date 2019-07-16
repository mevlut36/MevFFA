<?php

namespace Mev\task;

use Mev\Main;
use Mev\task\ScoreboardTask;

use pocketmine\block\Block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Armor;

use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ArmorInventory;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use pocketmine\scheduler\Task;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\level\Level;
use pocketmine\level\Position;

use pocketmine\utils\TextFormat as TF;
use pocketmine\math\Vector3;

class ClearMapTask extends Task {

	public $start = 5;
	public $player;
	public $plugin;
	
    public function __construct(Main $plugin, Player $player){
		$this->plugin = $plugin;
		$this->player = $player;
	}

    public function onRun($tick) {
        if($this->start != 0) {
        	$this->start--;
  			$this->player->sendMessage(TF::YELLOW . "[FFA] FFA-1 will be cleared in " . $this->start . " seconds");
		} 
		
        if($this->start == 1) {
			$this->reset();
		}
		
	}
	public function reset() {
				$this->plugin->getServer()->loadLevel("world");
				foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
					$p->getInventory()->clearAll();
					$p->teleport($this->plugin->getServer()->getLevelByName("world")->getSafeSpawn());
					$p->sendMessage(TF::BLUE . "The FFA world is being cleaned, please wait.");
				}
				$this->plugin->getServer()->unloadLevel($this->plugin->getServer()->getLevelByName("ffa"));
				$path = $this->plugin->getServer()->getDataPath();
				$this->recurse_copy($path."worlds/ffabackup",$path."worlds/ffa");
				$this->plugin->getServer()->loadLevel("ffa");
				foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
					$arenalevel = $this->plugin->getServer()->getLevelByName("world");
       	 		$arenaspawn = $arenalevel->getSafeSpawn();
      			  $p->teleport($arenaspawn);
				}
			}
		   
			public function recurse_copy($src,$dst) {
			$dir = opendir($src);
			@mkdir($dst);
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if ( is_dir($src . '/' . $file) ) {
						$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
					}
					else {
						copy($src . '/' . $file,$dst . '/' . $file);
					}
				}
			}
			closedir($dir);
			} 
}
