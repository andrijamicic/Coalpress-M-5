<?php
// the query
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_query = new WP_Query(
  array(
    'post_type' => 'Post',
    'post_status' => 'publish',
    'nopaging' => false,
    'posts_per_page' => 3,
    'order' => 'DESC',
    'orderby' => 'ID',
    'paged' => $paged
  )
);
?>
<!-- limit excpert (default is 50) -->
<?php
function excerpt($limit)
{
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt) >= $limit) {
    array_pop($excerpt);
    $excerpt = implode(" ", $excerpt) . '...';
  } else {
    $excerpt = implode(" ", $excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
  $excerpt = $excerpt . '... <a href="' . get_the_permalink() . '">more</a>';
  return $excerpt;
} ?>
<!-- limit content (default is max) -->
<?php
function content($limit)
{
  $content = explode(' ', get_the_content(), $limit);
  if (count($content) >= $limit) {
    array_pop($content);
    $content = implode(" ", $content) . '...';
  } else {
    $content = implode(" ", $content);
  }
  $content = preg_replace('/[.+]/', '', $content);
  $content = str_replace(']]>', ']]>', $content);
  $content = $content . '... <a href="' . get_the_permalink() . '">more</a>';
  return $content;
} ?>
<div id="home-slider">
  <div id="click" class="w3-content w3-display-container">
    <?php if ($post_query->have_posts()) : ?>
      <!-- the loop -->
      <?php
      $j = 0;
      while ($post_query->have_posts()) : $post_query->the_post(); ?>
        <div class="HomeSlides" style="color:blue;background-image: url('<?php echo get_the_post_thumbnail_url(); ?>')" style="width:100%">
          <p> <?php
              $i = 0;
              $value = get_the_category();
              $len = count($value);
              foreach ($value as $category) {
                if ($i == $len - 1) {
                  echo $category->name . "";
                } else {
                  echo $category->name . " | ";
                }
                ++$i;
              }  ?> </p>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <?php echo content(20); ; ?>
        </div>
        <?php $j = $j + 1 ?>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
  <button id="left-homeb" onclick="plusDivs(-1)">&#10094;</button>
  <div id="numbers" style="width:<?php echo ($j * 20); ?>px">
    <?php
    foreach (range(1, $j) as $number) {
      echo '<div class="number">' . $number . '</div>';
    } ?>
  </div>
  <button id="right-homeb" onclick="plusDivs(1)">&#10095;</button>
</div>
<script>
  "use strict";

  function _instanceof(left, right) {
    if (right != null && typeof Symbol !== "undefined" && right[Symbol.hasInstance]) {
      return !!right[Symbol.hasInstance](left);
    } else {
      return left instanceof right;
    }
  }

  function _classCallCheck(instance, Constructor) {
    if (!_instanceof(instance, Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  }

  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
  }

  //SWIPE CLASS
  var Swipe = /*#__PURE__*/ function() {
    function Swipe(elem) {
      var _this = this;

      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      _classCallCheck(this, Swipe);

      this.elem = elem;
      this.minDistance = options.minDistance || 100;
      this.maxTime = options.maxTime || 500;
      this.corners = options.corners || false;
      this.addListeners();
      this.events = {
        live: [],
        after: []
      };
      Swipe.directions().forEach(function(direction) {
        return _this.events[direction] = [];
      });
    }

    _createClass(Swipe, [{
      key: "addEventListener",
      value: function addEventListener(evt, bc) {
        var _this2 = this;

        var keys = Object.keys(this.events);

        if (keys.indexOf(evt) !== -1) {
          this.events[evt].push(bc);
          var i = this.events.length - 1;
          return {
            clear: function clear() {
              _this2.events[i] = undefined;
            }
          };
        } else {
          throw new Error("Event is not valid, use " + keys.join(", "));
        }
      }
    }, {
      key: "down",
      value: function down(e) {
        e.preventDefault();
        this.didDown = true;
        this.startTime = Date.now();
        this.startPos = Swipe.position(e);
      }
    }, {
      key: "move",
      value: function move(e) {
        e.preventDefault();

        if (!this.didDown) {
          return;
        }

        this.didSwipe = true;

        if (this.events.live.length > 0) {
          var offsets = Swipe.getOffsets(e, this.startPos);
          var directions = Swipe.getDirections(offsets, this.corners);
          var direction = Swipe.order(directions)[0];
          var distance = directions[direction];
          this.events.live.forEach(function(evt) {
            if (typeof evt === "function") {
              evt(direction, distance);
            }
          });
        }
      }
    }, {
      key: "up",
      value: function up(e) {
        e.preventDefault();
        this.didDown = false;

        if (!this.didSwipe) {
          return;
        }

        this.didSwipe = false;
        var elapsedTime = Date.now() - this.startTime;

        if (elapsedTime <= this.maxTime) {
          var offsets = Swipe.getOffsets(e, this.startPos);
          var directions = Swipe.getDirections(offsets, this.corners);
          var direction = Swipe.order(directions)[0];
          var distance = directions[direction];

          if (distance >= this.minDistance) {
            this.events.after.forEach(function(evt) {
              if (typeof evt === "function") {
                evt(direction, distance);
              }
            });
            this.events[direction].forEach(function(evt) {
              if (typeof evt === "function") {
                evt(distance);
              }
            });
          }
        }
      }
    }, {
      key: "addListeners",
      value: function addListeners() {
        var _this3 = this;

        this.elem.addEventListener("touchstart", function(e) {
          return _this3.down(e);
        });
        this.elem.addEventListener("mousedown", function(e) {
          return _this3.down(e);
        });
        this.elem.addEventListener("touchmove", function(e) {
          return _this3.move(e);
        });
        document.addEventListener("mousemove", function(e) {
          return _this3.move(e);
        });
        this.elem.addEventListener("touchend", function(e) {
          return _this3.up(e);
        });
        document.addEventListener("mouseup", function(e) {
          return _this3.up(e);
        });
      }
    }], [{
      key: "directions",
      value: function directions() {
        return ['left', 'right', 'up', 'down', 'leftup', 'leftdown', 'rightup', 'rightdown'];
      }
    }, {
      key: "position",
      value: function position(e) {
        return {
          x: e.pageX,
          y: e.pageY
        };
      }
    }, {
      key: "getOffsets",
      value: function getOffsets(e, startPos) {
        var newPos = Swipe.position(e);
        return {
          x: newPos.x - startPos.x,
          y: newPos.y - startPos.y
        };
      }
    }, {
      key: "getDirections",
      value: function getDirections(offsets, corners) {
        var directions = {};
        directions.left = offsets.x <= 0 ? Math.abs(offsets.x) : 0;
        directions.right = offsets.x >= 0 ? Math.abs(offsets.x) : 0;
        directions.up = offsets.y <= 0 ? Math.abs(offsets.y) : 0;
        directions.down = offsets.y >= 0 ? Math.abs(offsets.y) : 0;

        if (corners) {
          directions.leftup = Math.abs(directions.left + directions.up) / 1.5;
          directions.leftdown = Math.abs(directions.left + directions.down) / 1.5;
          directions.rightup = Math.abs(directions.right + directions.up) / 1.5;
          directions.rightdown = Math.abs(directions.right + directions.down) / 1.5;
        }

        return directions;
      }
    }, {
      key: "order",
      value: function order(directions) {
        return Object.keys(directions).sort(function(a, b) {
          return directions[b] - directions[a];
        });
      }
    }]);

    return Swipe;
  }(); //CODE FOR ANIMATION


  var box = document.querySelector('#click');
  var classes = Swipe.directions();
  var elem = box.firstChild;

  var runAnimation = function runAnimation(direction) {
    elem.innerHTML = direction;
    elem.classList.remove.apply(elem.classList, classes);
    setTimeout(function() {
      return elem.classList.add(direction);
    }, 1);
  }; //SWIPE INITIALIZATION


  var swipe = new Swipe(box, {
    corners: true,
    minDistance: 50
  });
  var afterEvent = swipe.addEventListener("after", function(direction) {
    if (direction == 'right') {
      plusDivs(-1);
    }

    if (direction == 'left') {
      plusDivs(1);
    } //  block of code to be executed if the condition is true

  });
  var liveEvent = swipe.addEventListener("live", function(direction) {
    elem.innerHTML = direction;
  }); //REMOVE EVENT WITH evt.clear();
  //SLIDER

  var slideIndex = 1;
  showDivs(slideIndex);

  function plusDivs(n) {
    showDivs(slideIndex += n);
  }

  function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("HomeSlides");
    var y = document.getElementsByClassName("number");

    if (n > x.length) {
      slideIndex = 1;
    }

    if (n < 1) {
      slideIndex = x.length;
    }

    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
      y[i].style.color = "black";
     // y[i].on('click', plusDivs(1));
    }

    x[slideIndex - 1].style.display = "block";
    y[slideIndex - 1].style.color = "red";
  }
</script>