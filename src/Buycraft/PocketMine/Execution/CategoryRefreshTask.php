<?php

namespace Buycraft\PocketMine\Execution;


use Buycraft\PocketMine\BuycraftPlugin;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CategoryRefreshTask extends AsyncTask
{
	private $pluginApi;

    public function __construct(BuycraftPlugin $plugin)
    {
		$this->pluginApi = $plugin->getPluginApi();
    }

    public function onRun()
    {
        try {
        	$this->setResult($this->pluginApi->basicGet("/listing", true, 10));
        } catch (\Exception $e) {
            $this->setResult(null);
        }
    }

    public function onCompletion(Server $server) {
    	$result = $this->getResult();
    	$plugin = BuycraftPlugin::getInstance();
    	if($result !== null) {
			$plugin->setCategories($result['categories']);

			$plugin->getLogger()->debug("Category refresh complete.");
		} else {
			$plugin->getLogger()->error(TextFormat::RED . "Unable to fetch category listing.");
		}
	}
}
