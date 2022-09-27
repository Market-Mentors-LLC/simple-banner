{{-- 
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      0.2.0
 *
 * @package    Market_Mentors_Simple_Banner
 */  
--}}
<figure class="header-banner dismissable" data-id="headernotification_1">
  <div class="dismissable-control">
    <i class="fas fa-window-close"></i>
  </div>
  <div class="urgent-icon">
    <i class="fas fa-exclamation"></i>
  </div>
  <div class="content">
    {{ $bannerContent }}
  </div>
</figure>
