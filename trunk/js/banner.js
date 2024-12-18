// JavaScript Document

/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Lee Underwood :: http://javascript.internet.com/ */

var bannerImg = new Array();
  // Enter the names of the images below
  bannerImg[0]="../skins/basic/customer/images/banners/home_banner.jpg";
  bannerImg[1]="../skins/basic/customer/images/banners/live_help_offline.gif";
  bannerImg[2]="../skins/basic/customer/images/banners/live_help_online.gif";

var newBanner = 0;
var totalBan = bannerImg.length;

function cycleBan() {
  newBanner++;
  if (newBanner == totalBan) {
    newBanner = 0;
  }
  document.banner.src=bannerImg[newBanner];
  // set the time below for length of image display
  // i.e., "4*1000" is 4 seconds
  setTimeout("cycleBan()", 4*1000);
}
window.onload=cycleBan;
