<?php namespace Pmietlicki\Todo\Components;

use Auth;
use Redirect;
use Stdclass;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Pmietlicki\Todo\Models\Task;


class Todo extends ComponentBase
{
    /**
     * This is a person's name
     * @var string
     */
    public $name;

/**
 * A collection of tasks
 * @var array
 */
    public $tasks;

    public function componentDetails()
    {
        return [
            'name'        => 'Todo Component',
            'description' => 'A database driven ToDo List'
        ];
    }

    public function defineProperties()
    {
        return [
            'max' => [
                'description'       => 'The most amount of todo items allowed',
                'title'             => 'Max items',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Max Items value is required and should be integer.'
            ]
        ];
    }

    private function getTasks()
    {
        return Task::where('user_id','=',Auth::getUser()->id)->get()->toArray();
    }

    public function onRun()
    {
        if (Auth::getUser())
            $this->name = Auth::getUser()->name;
        else
            $this->name = "Not logged in !";

        if (Auth::check())
        {
            $this->tasks = new StdClass;
            $this->tasks = $this->getTasks();
        }
    }

    public function onAddItem()
    {

        if (!Auth::check())
             throw new ApplicationException('You must be logged in to perform this action!');
        else
        {

            $items = Task::lists('title');
            if (count($items) >= $this->property('max')) {
                throw new \Exception(sprintf('Sorry only %s items are allowed.', $this->property('max')));
            }

            if (($newItem = post('task')) != '') {
                $task = new Task;
                $task->user_id = Auth::getUser()->id;
                $task->title = $newItem;
                $task->save();
            }

            $this->page['tasks'] = $this->getTasks();
        }
    }

    public function onDeleteItem()
    {
        if (!Auth::check())
             throw new ApplicationException('You must be logged in to perform this action!');
        else
        {
            $task = Task::whereTitle(post('title'))->where('user_id','=',Auth::getUser()->id);
            if (isSet($task) && !empty($task))
            {
                $task->delete();
                $this->page['tasks'] = $this->getTasks();
            }
        }

    }

        public function onCompleteItem()
    {
        if (!Auth::check())
             throw new ApplicationException('You must be logged in to perform this action!');
        else
        {
            $task = Task::whereTitle(post('title'))->where('user_id','=',Auth::getUser()->id)->first();
            if (isSet($task) && !empty($task))
            {
                $task->is_completed = !$task->is_completed;
                $task->save();
                $this->page['tasks'] = $this->getTasks();
            }
        }

    }


}