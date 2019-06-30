<?php

namespace Mev\task;

use Mev\Main;
use Mev\task\ScoreboardTask;
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
use pocketmine\level\Position;
use pocketmine\utils\TextFormat as TF;
use pocketmine\math\Vector3;

class TeleportTask extends Task {

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
  			$this->player->sendMessage(TF::YELLOW . "[FFA] You will teleported in " . $this->start . " seconds");
		} 
        if($this->start == 1) {
        	$x = mt_rand(20, 40);
           	 $y = mt_rand(40, 41);
           	 $z = mt_rand(20, 40);
           
          	  $arenalevel = $this->plugin->getServer()->getLevelByName("ffa");
       	 	$arenaspawn = $arenalevel->getSafeSpawn();
       	 	$this->player->teleport($arenaspawn, $y, $z);
            	$this->player->sendMessage(TF::DARK_PURPLE . "You have joined FFA-1.");
                $this->player->setGamemode(0);
                $this->player->getInventory()->clearAll();
      		  $helmet = Item::get(306); 
				$chestplate = Item::get(307);
				$leggings = Item::get(308); 
				$boots = Item::get(309); 
				$sword = ItemFactory::get(Item::DIAMOND_SWORD); 
				$sword->clearCustomName(); 
				$sword->setCustomName("§bSword"); 
				$this->player->getArmorInventory()->setHelmet($helmet); 
				$this->player->getArmorInventory()->setChestplate($chestplate); 
				$this->player->getArmorInventory()->setLeggings($leggings); 
				$this->player->getArmorInventory()->setBoots($boots); 
				$this->player->getInventory()->addItem($sword);
        }
    }
}