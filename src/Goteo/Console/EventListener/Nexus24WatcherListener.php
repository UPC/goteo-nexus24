<?php
/*
 * This file is part of the Goteo Package.
 *
 * (c) Platoniq y Fundación Goteo <fundacion@goteo.org>
 *
 * For the full copyright and license information, please view the README.md
 * and LICENSE files that was distributed with this source code.
 */

namespace Goteo\Console\EventListener;

// use Goteo\Application\Config;
// use Goteo\Console\UsersSend;
use Goteo\Console\EventListener\ConsoleWatcherListener;

use Goteo\Application\Exception\DuplicatedEventException;
use Goteo\Model\Project;
use Goteo\Model\Event;

//

class Nexus24WatcherListener extends ConsoleWatcherListener {

    /**
     * Executes the action of sending a message to the targets
     * Ensures that the sending is a unique event so no duplicates messages arrives to the user
     *
     * @param  Project $project    Project object to process
     * @param  string  $template   Message identifier (from the UsersSend class)
     * @param  array   $target     Receiver, the owner or the consultants
     * @param  string  $extra_hash Used to add some extra identification to the Event action to allow sending the same message more than once
     */
    private function send(Project $project, $template, $target = ['owner'], $extra_hash = '') {
        if(!is_array($target)) $target = [$target];
        foreach($target as $to) {
            if(!in_array($to, ['owner', 'consultants'])) {
                throw new \LogicException("Target [$to] not allowed");
            }
            try {
                $action = [$project->id, $to, $template];
                if($extra_hash) $action[] = $extra_hash;
                $event = new Event($action);

            } catch(DuplicatedEventException $e) {
                $this->warning('Duplicated event', ['action' => $e->getMessage(), $project, 'event' => "$to:$template"]);
                return;
            }
            $event->fire(function() use ($project, $template, $to) {
                // DO NOT SEND MAIL
                // UsersSend::setURL(Config::getUrl($project->lang));
                // if('owner' === $to) UsersSend::toOwner($template, $project);
                // if('consultants' === $to) UsersSend::toConsultants($template, $project);
            });

            $this->notice("Sent message to $to", [$project, 'event' => "$to:$template"]);
        }
    }
}
