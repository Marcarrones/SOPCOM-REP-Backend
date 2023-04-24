<?php

class GoalView {

    public function buildGoalList($goals) {
        $result = [];
        foreach($goals as $goal) {
            $pos = array_search($goal['id'], array_column($result, 'id'));
            if($pos !== false) {
                $result[$pos][] = Array('id' => $goal['id'], 'name' => $goal['name']);
            } else {
                $result[] = Array('id' => $goal['id'], 
                            'name' => $goal['name']);
            }
        }
        return $result;
    }

    public function buildGoal($goal) {
        $result = $goal[0];
        return $result;
    }
    
}