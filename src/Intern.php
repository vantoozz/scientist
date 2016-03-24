<?php

namespace Scientist;

use Scientist\Matchers\Matcher;

/**
 * Class Intern
 *
 * Interns do all the hard work, naturally. They'll execute an experiment within
 * the Laboratory, and record the results.
 *
 * @package \Scientist
 */
class Intern
{
    /**
     * Run an experiment, and retrieve the result.
     *
     * @param \Scientist\Experiment $experiment
     *
     * @return \Scientist\Report
     */
    public function run(Experiment $experiment)
    {
        $control = $this->runControl($experiment);
        $trials  = $this->runTrials($experiment);

        $this->determineMatches($experiment->getMatcher(), $control, $trials);

        return new Report($experiment->getName(), $control, $trials);
    }

    /**
     * Run the control callback, and record its execution state.
     *
     * @param \Scientist\Experiment $experiment
     *
     * @return \Scientist\Result
     */
    protected function runControl(Experiment $experiment)
    {
        $machine = (new Machine($experiment->getControl(), $experiment->getParams()));

        return $machine->execute();
    }

    /**
     * Run trial callbacks and record their execution state.
     *
     * @param \Scientist\Experiment $experiment
     *
     * @return \Scientist\Result[]
     */
    protected function runTrials(Experiment $experiment)
    {
        $executions = array();

        foreach ($experiment->getTrials() as $name => $trial) {
            $machine = (new Machine(
                $trial,
                $experiment->getParams(),
                true
            ));
            $executions[$name] = $machine->execute();
        }

        return $executions;
    }

    /**
     * Determine whether trial results match the control.
     *
     * @param \Scientist\Matchers\Matcher $matcher
     * @param \Scientist\Result           $control
     * @param \Scientist\Result[]         $trials
     */
    protected function determineMatches(Matcher $matcher, Result $control, array $trials = array())
    {
        foreach ($trials as $trial) {
            if ($matcher->match($control->getValue(), $trial->getValue())) {
                $trial->setMatch(true);
            }
        }
    }
}
