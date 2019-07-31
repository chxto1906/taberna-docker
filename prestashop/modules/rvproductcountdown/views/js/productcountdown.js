/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
$(function () {
    $('.productcountdown').each(function(){
        var labels = rvpc_labels,
            to = $(this).data('to'),
            template = _.template(rvpc_countdown_tpl),
            $rvpc = $(this).find('.rvpc-main');
        if (rvpc_show_weeks) {
            var currDate = '00:00:00:00:00';
            var nextDate = '00:00:00:00:00';
        } else {
            var currDate = '00:00:00:00';
            var nextDate = '00:00:00:00';
        }

        // Parse countdown string to an object
        function strfobj(str) {
            var pieces = str.split(':');
            var obj = {};
            labels.forEach(function(label, i) {
                obj[label] = pieces[i]
            });
            return obj;
        }
        // Return the time components that diffs
        function diff(obj1, obj2) {
            var diff = [];
            labels.forEach(function(key) {
                if (obj1[key] !== obj2[key]) {
                    diff.push(key);
                }
            });
            return diff;
        }
        // Build the layout
        var initData = strfobj(currDate);
        labels.forEach(function(label, i) {
            $rvpc.append(template({
                curr: initData[label],
                next: initData[label],
                label: label,
                label_lang: rvpc_labels_lang[label]
            }));
        });
        // Starts the countdown
        $rvpc.countdown(to, function(event) {
            var data;
            if (rvpc_show_weeks)
                var newDate = event.strftime('%w:%d:%H:%M:%S');
            else
                var newDate = event.strftime('%D:%H:%M:%S');

            if (newDate !== nextDate) {
                currDate = nextDate;
                nextDate = newDate;
                // Setup the data
                data = {
                    'curr': strfobj(currDate),
                    'next': strfobj(nextDate)
                };
                // Apply the new values to each node that changed
                diff(data.curr, data.next).forEach(function(label) {
                    var selector = '.%s'.replace(/%s/, label),
                        $node = $rvpc.find(selector);
                    // Update the node
                    $node.removeClass('flip hidden');
                    $node.find('.curr').text(data.curr[label]);
                    $node.find('.next').text(data.next[label]);
                    // Wait for a repaint to then flip
                    _.delay(function($node) {
                        $node.addClass('flip');
                    }, 50, $node);
                });
            }
        });
    });
});

var rvpc_countdown_tpl = '' +
        '<div class="time <%= label %>">' +
            '<span class="rvcount curr top"><%= curr %></span>' +
            '<span class="rvcount next top"><%= next %></span>' +
            '<span class="rvcount next bottom"><%= next %></span>' +
            '<span class="rvcount curr bottom"><%= curr %></span>' +
            '<span class="label"><%= label_lang.length < 6 ? label_lang : label_lang.substr(0, 3)  %></span>' +
    '</div>';