<div class="wrap">
<p><?php 

load_plugin_textdomain(RUNNERSLOG,PLUGINDIR.'runners-log/languages','runners-log/languages');

echo "<h2>" . __( 'Runners Log Stats and Graph' ) . "</h2>"; ?></p>
<h3><?php _e("Below the graphs you'll find the code you need to use it in a page, post or template to insert a simular graph as shown.", RUNNERSLOG)?></h3>

<p><?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?></p>
<div class="block-content"><?php _e("In pages or posts use:", RUNNERSLOG)?> <code>[runners_log_graph]</code> <?php _e('in a template ', RUNNERSLOG)?> <code>&lt;?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_pie_distance]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_pie_hours]</code>  <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_pie_calories]</code>  <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_bar_distance]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_bar_hours]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_bar_calories]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_graphmini_distance]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_graphmini_hours]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?&gt;</code></div> 

<p><?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?></p>
<div class="block-content"><?php _e('In pages or posts use:', RUNNERSLOG)?> <code>[runners_log_graphmini_calories]</code> <?php_e('in a template  ', RUNNERSLOG)?><code>&lt;?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?&gt;</code></div> 

</div>