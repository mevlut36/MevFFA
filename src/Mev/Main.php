<?php

namespace Mev;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;

use pocketmine\event\block\BlockPlaceEvent;

use pocketmine\scheduler\Task;

use Mev\task\TeleportTask;
use Mev\task\ScoreboardTask;
use Mev\task\DeathTask;

use pocketmine\math\Vector3;
use pocketmine\level\Position;

use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

class Main extends PluginBase implements Listener{
   
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info(TF::GREEN . "MevFFA Enabled !");
        $this->getServer()->loadLevel("ffa");
    }
    public function onDisable(){
        $this->getServer()->getLogger()->info(TF::RED . "MevFFA disabled !");
    }
    public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool{
    switch(strtolower($command->getName())){
      case "ffa":
      if(!isset($args[0])){
                $player->sendMessage(TF::RED."Usage: /ffa help");
                return true;
                break;
            }
        if(isset($args[0]) and $player instanceof Player) {
        switch($args[0]) {
            case "help":
                $player->sendMessage(TF::GRAY . "-----" . TF::GOLD . "FFA Commands" . TF::GRAY . "-----");
                $player->sendMessage(TF::GRAY . "-" . TF::LIGHT_PURPLE . " /ffa help (you are already here lol)");
                $player->sendMessage(TF::GRAY . "-" . TF::LIGHT_PURPLE . " /ffa join");
                $player->sendMessage(TF::GRAY . "-" . TF::LIGHT_PURPLE . " /ffa clear [Admin command]");
                $player->sendMessage(TF::GRAY . "-----()-----");
                return true;
                break;
            case "join":
            $ffa = $player->getServer()->getLevelByName("ffa");
            $ffa2 = $player->getServer()->getLevelByName("ffa-2");
            $name = $player->getName();
            $this->players[] = $player;
            
            if(count($this->getServer()->getLevelByName("ffa")->getPlayers()) < 40) {
            	$this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this), 6 * 20);
            	$this->getScheduler()->scheduleRepeatingTask(new TeleportTask($this, $player), 20);
           	
             }
                if(count($this->getServer()->getLevelByName("ffa")->getPlayers()) >= 40) {
                    $player->sendMessage(TF::RED . "[Warning] The FFA-1 is full.");
            } 
                return true;
                break;

                case "clear":
                $ffa = $player->getServer()->getLevelByName("ffa");
                $ffa2 = $player->getServer()->getLevelByName("ffa-2");
                foreach($this->getServer()->getLevelByName("ffa")) {
                    $this->getScheduler()->scheduleRepeatingTask(new ClearMapTask($this, $player), 20);
                 }
                    return true;
                    break;
    }
  }
 } 
}

    public function onDrop(PlayerDropItemEvent $event){
        $event->setCancelled(true);
    }

    public function onDeath(PlayerDeathEvent $event) {
	    $player = $event->getPlayer();
	    $player->setGamemode(3);
	    $this->getScheduler()->scheduleRepeatingTask(new DeathTask($this, $player), 20);
	}
	
    public function Scoreboard(Player $player){
		$x = $player->getFloorX();
		$y = $player->getFloorY();
		$z = $player->getFloorZ();
		$players = count($this->getServer()->getLevelByName("ffa")->getPlayers());
		$name = $player->getName();
        $score = 3;
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = "sidebar";
        $pk->objectiveName = "test";
        $pk->displayName = "§9- §e§lFFA§r §9-";
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;
        $player->sendDataPacket($pk);


        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "§8----------------";
        $entrie->score = 1;
        $entrie->scoreboardId = 1;
        $pk01 = new SetScorePacket();
        $pk01->type = 0;
        $pk01->entries[] = $entrie;
        $player->sendDataPacket($pk01);

        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "Players: " . $players . "/40";
        $entrie->score = 2;
        $entrie->scoreboardId = 2;
        $pk3 = new SetScorePacket();
        $pk3->type = 0;
        $pk3->entries[] = $entrie;
        $player->sendDataPacket($pk3);
		$date = date("H:i:s");
        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = TF::GRAY . $date;

        $entrie->score = $score;
        $entrie->scoreboardId = $score;
        $pk4 = new SetScorePacket();
        $pk4->type = 0;
        $pk4->entries[] = $entrie;
        $player->sendDataPacket($pk4);


        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "                     ";
        $entrie->score = 4;
        $entrie->scoreboardId = 4;
        $pk5 = new SetScorePacket();
        $pk5->type = 0;
        $pk5->entries[] = $entrie;
        $player->sendDataPacket($pk5);

        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "§fDeath:";
        $entrie->score = 5;
        $entrie->scoreboardId = 5;
        $pk1 = new SetScorePacket();
        $pk1->type = 0;
        $pk1->entries[] = $entrie;
        $player->sendDataPacket($pk1);

        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "§fKills: ";
        $entrie->score = 6;
        $entrie->scoreboardId = 6;
        $pk6 = new SetScorePacket();
        $pk6->type = 0;
        $pk6->entries[] = $entrie;
        $player->sendDataPacket($pk6);

        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "";
        $entrie->score = 7;
        $entrie->scoreboardId = 7;
        $pk2 = new SetScorePacket();
        $pk2->type = 0;
        $pk2->entries[] = $entrie;
        $player->sendDataPacket($pk2);

        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = "§fX: " . $x . " Y:" . $y . " Z:" . $z;
        $entrie->score = 8;
        $entrie->scoreboardId = 8;
        $pk7 = new SetScorePacket();
        $pk7->type = 0;
        $pk7->entries[] = $entrie;
        $player->sendDataPacket($pk7);

        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entrie->customName = str_repeat(" ", 5) . "§6  localhost";
        $entrie->score = 9;
        $entrie->scoreboardId = 9;
        $pk7 = new SetScorePacket();
        $pk7->type = 0;
        $pk7->entries[] = $entrie;
        $player->sendDataPacket($pk7);
    }
}
