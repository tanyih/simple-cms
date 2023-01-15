<?php
   // make sure only admin can access
  if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
  }

  // load post data
  $post = Post::getPostByID( $_GET['id'] );

  // step 1: set CSRF token
  CSRF::generateToken( 'edit_post_form' );


  // step 2: make sure post request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check

    

      // if both password & confirm_password fields are empty, 
      // skip error checking for both fields.
      $rules = [
        'title' => 'required',
        'content' => 'content_check',
        'status' => 'required',
        'csrf_token' => 'edit_post_form_csrf_token'
      ];

      

      // if eiter password & confirm_password fields are not empty, 
      // do error check for both fields
      $error = FormValidation::validate(
        $_POST,
        $rules
      );

      // if content changed, make sure it cannot belongs to another post
      // we compare content from database and form for content changes
      if ( $post['content'] !== $_POST['content'] ) {
        // do database check to make sure new content wasn't already in use
        $error .= FormValidation::checkcontentUniqueness( $_POST['content'] );
      }

      // make sure there is no error
      if ( !$error ) {
        // step 4: update post
        post::update(
          $post['id'], // id
          $_POST['title'], // title
          $_POST['content'],// content
          $_POST['status'], // status
          ( $is_password_changed ? $_POST['password'] : null ) // password update if available
        );

        // step 5: remove the CSRF token
        CSRF::removeToken( 'edit_post_form' );

        // Step 6: redirect to manage posts page
        header("Location: /manage-posts");
        exit;

      }
  }
   

   require  "parts/header.php";
?> 
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit Post</h1>
      </div>
      <div class="card mb-2 p-4">
        <form>
          <div class="mb-3">
            <label for="post-title" class="form-label">Title</label>
            <input
              type="text"
              class="form-control"
              id="post-title"
              value="Post 1"
            />
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Content</label>
            <textarea class="form-control" id="post-content" rows="10">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris purus risus, euismod ac tristique in, suscipit quis quam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum eget dapibus nibh. Pellentesque nec maximus odio. In pretium diam metus, sed suscipit neque porttitor vitae. Vestibulum a mattis eros. Integer fermentum arcu dolor, nec interdum sem tincidunt in. Cras malesuada a neque ut sodales. Nulla facilisi.

Phasellus sodales arcu quis felis sollicitudin vehicula. Aliquam viverra sem ac bibendum tincidunt. Donec pulvinar id purus sagittis laoreet. Sed aliquet ac nisi vehicula rutrum. Proin non risus et erat rhoncus aliquet. Nam sollicitudin facilisis elit, a consequat arcu placerat eu. Pellentesque euismod et est quis faucibus.

Curabitur sit amet nisl feugiat, efficitur nibh et, efficitur ex. Morbi nec fringilla nisl. Praesent blandit pellentesque urna, a tristique nunc lacinia quis. Integer semper cursus lectus, ac hendrerit mi volutpat sit amet. Etiam iaculis arcu eget augue sollicitudin, vel luctus lorem vulputate. Donec euismod eu dolor interdum efficitur. Vestibulum finibus, lectus sed condimentum ornare, velit nisi malesuada ligula, eget posuere augue metus et dolor. Nunc purus eros, ultricies in sapien quis, sagittis posuere risus.
                        </textarea
            >
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Status</label>
            <select class="form-control" id="post-status" name="status">
              <option value="review">Pending for Review</option>
              <option value="publish">Publish</option>
            </select>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-posts" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Posts</a
        >
      </div>
    </div>

    <?php
   require "parts/footer.php";
?>