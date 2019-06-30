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
        if($this->start == 0) {
        	$lv = $this->getServer()->getLevelByName("ffa");
		//use not this in a world random lol
		
			for ($x = -200; $x <= 200; $x++){
 			for ($y = 1; $y <= 100; $y++){
  			for ($z = -200; $z <= 200; $z++){
    			if ($lv->getBlockIdAt($x,$y,$z) == Item::SANDSTONE){ 
     				$lv->setBlock(new Vector3($x,$y,$z), Block::get(0));
           				}
     				 }
     			}
 		    }
		    
		}
		
    }
}
