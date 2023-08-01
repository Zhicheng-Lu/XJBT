<?php
function check_valid($position_teams, $temp_team) {
	if ($position_teams["group_index"] == $temp_team["group_index"]) return False;
	return True;
}

function bipartite($positions, $current_position, $teams) {
	for ($j=0; $j < sizeof($teams); $j++) {
		if ($teams[$j]["availability"] == "available") {
			if (!check_valid($positions[$current_position], $teams[$j])) {
				$teams[$j]["availability"] = "infeasible";
			}
			else {
				$s = build_graph($positions, $current_position, $teams, $j);
				$max_flow = find_max_flow($s);
				// echo $max_flow;echo sizeof($s->get_children());
				if ($max_flow != sizeof($s->get_children())) {
					$teams[$j]["availability"] = "infeasible";
				}
			}
		}
	}

	return $teams;
}

function build_graph($positions, $current_position, $teams, $temp_team) {
	$s = new Node();
	$s->id = "s";
	$t = new Node();
	$t->id = "t";
	$id = 1;

	$nodes = array();
	for ($i=$current_position+1; $i < sizeof($positions); $i++) { 
		$node = new Node();
		$node->id = $id;
		$id += 1;
		$edge = new Edge();
		$edge->set_parent($s);
		$edge->set_child($node);
		$s->add_child($edge);
		$node->add_parent($edge);
		array_push($nodes, $node);
	}

	for ($j=0; $j < sizeof($teams); $j++) { 
		if (($teams[$j]["availability"] == "available" || $teams[$j]["availability"] == "infeasible") && $j != $temp_team) {
			$node = new Node();
			$node->id = $id;
			$id += 1;
			$edge = new Edge();
			$edge->set_parent($node);
			$edge->set_child($t);
			$node->add_child($edge);
			$t->add_parent($edge);

			for ($i=$current_position+1; $i < sizeof($positions); $i++) {
				if (check_valid($positions[$i], $teams[$j])) {
					$edge = new Edge();
					$edge->set_parent($nodes[$i-$current_position-1]);
					$edge->set_child($node);
					$nodes[$i-$current_position-1]->add_child($edge);
					$node->add_parent($edge);
				}
			}
		}
	}

	return $s;
}

class Node {
	public $parents = array();
	public $children = array();
	public $id;

	function add_parent($parent) {
		array_push($this->parents, $parent);
	}

	function add_child($child) {
		array_push($this->children, $child);
	}

	function get_parents() {
		return $this->parents;
	}

	function get_children() {
		return $this->children;
	}
}

class Edge {
	public $parent;
	public $child;
	public $forward_residual = 1;
	public $backward_residual = 0;

	function set_parent($parent) {
		$this->parent = $parent;
	}

	function set_child($child) {
		$this->child = $child;
	}

	function get_parent() {
		return $this->parent;
	}

	function get_child() {
		return $this->child;
	}
}


function get_type($team) {
	if ($team["team_name"] == $team["nationality"]) return "nation";
	else return "club";
}

function find_max_flow($s) {
	$counter = 0;
	$searching = True;
	while ($searching) {
		$searching = False;
		for ($i=0; $i < sizeof($s->get_children()); $i++) {
			$children = $s->get_children();
			$child_edge = $children[$i];
			if ($child_edge->forward_residual == 1) {
				if ($on_path = find_path($child_edge->get_child())) {
					update_flow($s, $on_path);
					$searching = True;
					$counter += 1;
					break;
				}
			}
		}
	}

	return $counter;
}

function find_path($node) {
	$visiteds = array();
	$on_path = array();

	$pointer = $node;
	$valid_backward = False;
	while (True) {
		if (!$valid_backward) {
			array_push($visiteds, $pointer->id);
			array_push($on_path, $pointer->id);
		}
		$valid_next_step = False;
		$valid_backward = False;

		for ($i=0; $i < sizeof($pointer->get_children()); $i++) { 
			$children = $pointer->get_children();
			$child_edge = $children[$i];
			if ($child_edge->forward_residual == 1) {
				$if_visited = False;
				foreach ($visiteds as $visited) {
					if ($visited == $child_edge->get_child()->id) {
						$if_visited = True;
					}
				}
				if (!$if_visited) {
					$pointer = $child_edge->get_child();
					$valid_next_step = True;
					break;
				}
			}
		}
		if ($valid_next_step) continue;
		for ($i=0; $i < sizeof($pointer->get_parents()); $i++) { 
			$parents = $pointer->get_parents();
			$parent_edge = $parents[$i];
			if ($parent_edge->backward_residual == 1) {
				foreach ($visiteds as $visited) {
					if ($visited == $parent_edge->get_parent()->id) {
						$if_visited = True;
					}
				}
				if (!$if_visited) {
					$pointer = $parent_edge->get_parent();
					$valid_next_step = True;
					break;
				}
			}
		}

		if ($pointer->id == "t") return $on_path;

		if (!$valid_next_step) {
			array_pop($on_path);
			if (sizeof($on_path) == 0) return False;
			$previous_id = $on_path[sizeof($on_path)-1];
			for ($i=0; $i < sizeof($pointer->get_children()); $i++) { 
				$children = $pointer->get_children();
				$child_edge = $children[$i];
				if ($child_edge->get_child()->id == $previous_id) {
					$pointer = $child_edge->get_child();
					$valid_backward = True;
					break;
				}
			}
			if ($valid_backward) continue;
			for ($i=0; $i < sizeof($pointer->get_parents()); $i++) { 
				$parents = $pointer->get_parents();
				$parent_edge = $parents[$i];
				if ($parent_edge->get_parent()->id == $previous_id) {
					$pointer = $parent_edge->get_parent();
					$valid_backward = True;
					break;
				}
			}
		}
	}
}

function update_flow($s, $on_path) {
	$current_node = $s;
	array_push($on_path, "t");

	foreach ($on_path as $node_id) {
		for ($i=0; $i < sizeof($current_node->get_children()); $i++) { 
			$children = $current_node->get_children();
			$child_edge = $children[$i];
			if ($child_edge->get_child()->id == $node_id) {
				$child_edge->forward_residual = 0;
				$child_edge->backward_residual = 1;
				$current_node = $child_edge->get_child();
			}
		}
		for ($i=0; $i < sizeof($current_node->get_parents()); $i++) { 
			$parents = $current_node->get_parents();
			$parent_edge = $parents[$i];
			if ($parent_edge->get_parent()->id == $node_id) {
				$parent_edge->forward_residual = 1;
				$parent_edge->backward_residual = 0;
				$current_node = $parent_edge->get_parent();
			}
		}
	}
}
?>