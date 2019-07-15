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

class ClearTest extends Task {

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
        if($this->start == 0) {
			$ffa = $this->plugin->getServer()->getLevelByName("ffa");
			for ($x = -200; $x <= 200; $x++){
 			for ($y = 1; $y <= 100; $y++){
  			for ($z = -200; $z <= 200; $z++){
    			if ($ffa->getBlockIdAt($x,$y,$z) == Item::SANDSTONE){ 
     				$ffa->setBlock(new Vector3($x,$y,$z), Block::get(0));
           				}
     				 }
     			}
 		    }
		    
		}
		
	}
	public function reset() {
				$this->getServer()->loadLevel("world");
				foreach($this->getServer()->getOnlinePlayers() as $p){
					$p->getInventory()->clearAll();
					$p->teleport($this->getServer()->getLevelByName("world")->getSafeSpawn());
					$p->sendMessage(TF:: . "The FFA world is being cleaned, please wait.")
				}
				$this->getServer()->unloadLevel($this->getServer()->getLevelByName("ffa"));
				$path = $this->getServer()->getDataPath();
				$this->recurse_copy($path."worlds/ffabackup",$path."worlds/ffa");
				$this->getServer()->loadLevel("ffa");
				foreach($this->getServer()->getOnlinePlayers() as $p){
					$this->getScheduler()->scheduleRepeatingTask(new TeleportTask($this, $player), 20);
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
