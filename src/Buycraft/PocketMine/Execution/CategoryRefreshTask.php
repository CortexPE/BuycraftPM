<?php

namespace Buycraft\PocketMine\Execution;


use Buycraft\PocketMine\BuycraftPlugin;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CategoryRefreshTask extends AsyncTask
{
	private $pluginApi;
    private $plugin;

    public function __construct(BuycraftPlugin $plugin)
    {
        $this->plugin = $plugin;
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
    	if($result !== null) {
			$this->plugin->setCategories($result['categories']);

			$this->plugin->getLogger()->debug("Category refresh complete.");
		} else {
			$this->plugin->getLogger()->error(TextFormat::RED . "Unable to fetch category listing.");
		}
	}
}
