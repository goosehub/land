<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Use this where needed for debugging
    // echo '<br>' . $this->db->last_query() . '<br>';

Class game_model extends CI_Model
{
 // Get world by id
 function get_world($world_id)
 {
    $this->db->select('*');
    $this->db->from('world');
    $this->db->where('id', $world_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return isset($result[0]) ? $result[0] : false;
 }
 // Get world by slug
 function get_world_by_slug_or_id($slug)
 {
    $this->db->select('*');
    $this->db->from('world');
    $this->db->where('slug', $slug);
    $this->db->or_where('id', $slug);
    $this->db->limit(1);
    $query = $this->db->get();
    $result = $query->result_array();
    return isset($result[0]) ? $result[0] : false;
 }
 // Get all lands
 function get_all_lands_in_world($world_key)
 {
    $this->db->select('*');
    $this->db->from('land');
    $this->db->where('world_key', $world_key);
    $query = $this->db->get();
    return $query->result_array();
 }
 // Get single land
 function get_single_land($world_key, $coord_slug)
 {
    $this->db->select('*');
    $this->db->from('land');
    $this->db->where('coord_slug', $coord_slug);
    $this->db->where('world_key', $world_key);
    $query = $this->db->get();
    $result = $query->result_array();
    return isset($result[0]) ? $result[0] : false;
 }
 // Update land data
 function update_land_data($world_key, $claimed, $coord_slug, $lat, $lng, $account_key, $land_name, $price, $charge, $charge_duration, $content, $primary_color)
 {
    $data = array(
        'claimed' => $claimed,
        'coord_slug' => $coord_slug,
        'lat' => $lat,
        'lng' => $lng,
        'account_key' => $account_key,
        'land_name' => $land_name,
        'price' => $price,
        'charge' => $charge,
        'charge_duration' => $charge_duration,
        'content' => $content,
        'primary_color' => $primary_color
    );
    $this->db->where('coord_slug', $coord_slug);
    $this->db->where('world_key', $world_key);
    $this->db->update('land', $data);
    return true;
 }
 // Update cash in account
 function update_account_cash_by_account_id($account_id, $cash)
 {
    // Seller add cash
    $data = array(
        'cash' => $cash
    );
    $this->db->where('id', $account_id);
    $this->db->update('account', $data);
    return true;
 }
 // Get projected tax
 function get_sum_and_count_of_account_land($account_id)
 {
    $this->db->select('SUM(price) as sum, COUNT(*) as count');
    $this->db->from('land');
    $this->db->where('account_key', $account_id);
    $query = $this->db->get();
    $result = $query->result_array();
    return isset($result[0]) ? $result[0] : 0;
 }

}
?>