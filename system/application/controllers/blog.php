<?php

class Blog extends Controller
{
	public $data;
	
	public $blog_id = "1";
	
	public function __construct()
	{
		parent::__construct();
		$this->data['ci_version'] = CI_VERSION;

		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form'); 
		$this->load->helper('markdown');
		$this->load->library('session');
		
		$this->output->enable_profiler(TRUE);
	}
	
	public function index()
	{
		$this->data['title'] = "Home";
		
		$b = new Blogmodel();
		$b->get($this->blog_id);
		
		$p = new Blogpost();
		
//		if($p->where('blog_id = ' . $b->id)->count() > 0)
		{
			$p->limit(10);
			$p->where(array('deleted' => 'false'));
			$p->order_by('date', 'desc')->get();
			$this->data['posts'] = $p->all;			
		}
		
		$this->load->view('recent_view', $this->data);
	}
	
	public function tagged($tag = 'untagged')
	{
		$this->data['title'] = "Entries containing " . $tag;
		
		$p = new Blogpost();
		
		$p->like('tags', $tag);
		$p->order_by('date', 'desc');
		$p->get();
		
		$this->data['posts'] = $p->all;
		
		$this->load->view('recent_view', $this->data);
	}
	
	public function flag($kind = null, $id = null)
	{
		if(isset($kind) and isset($id))
		{
			if($kind == 'post')
			{
				$notices = array();
				$notices[] = "Post flagging is not enabled at this time.";
				$this->session->set_userdata('notices', $notices);
				redirect('/', 'blog');
			}
			else if($kind == 'comment')
			{
				$c = new Blogcomment();
				
				$c->get_where(array('id' => $id));
				$c->flagged = "true";
				
				$f = new Blogflag();
				if($f->exists($id, $_SERVER['REMOTE_ADDR']))
				{
					$this->data['messages']['notices'][] = "You have already flagged this comment.";
					$this->comments($c->post_id);
				}
				else
				{
					if($f->save() and $c->save())
					{
						$this->data['messages']['success'][] = "Comment flagged successfully.";
						$this->comments($c->post_id);
					}
					else
					{
						$this->data['messages']['errors'][] = "Error flagging comment";
						$this->data['messages']['errors'][] = $c->error->string;
						$this->data['messages']['errors'][] = $f->error->string;
						$this->comments($c->post_id);
					}
				}
			}
			else
			{
				$this->data['messages']['errors'][] = "Unable to determine what you are trying to flag.";
				$this->index();
			}
		}
		else
		{
			$this->data['messages']['errors'][] = "Unable to flag post or comment because neither were specified";
			$this->index();
		}
	}
	
	public function undelete($id)
	{
		$p = new Blogpost();
		
		if(!$this->session->userdata('loggedin'))
		{
			$messages = array();
			$messages[] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to modify an entry.';
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');		
		}
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));
			
			$p->deleted = "false";
			
			if($p->save())
			{
				$successes = array();
				$successes[] = "Post successfully undeleted.";
				$this->session->set_userdata('successes', $successes);
				redirect('/', 'blog');
				
			}
			else
			{
				$messages = array();
				$messages[] = "Error undeleting post.";
				$this->session->set_userdata('successes', $messages);
				redirect('/blog/edit/' . $id, 'blog');
			}
		}
		else
		{
			$messages = array();
			$messages[] = "Post could not be undeleted because it could not be found.";
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');
		}
	}
	
	public function delete($id)
	{
		$p = new Blogpost();
		
		if(!$this->session->userdata('loggedin'))
		{
			$messages = array();
			$messages[] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to modify an entry.';
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');		
		}
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));
			
			$p->deleted = "true";
			
			if($p->save())
			{
				$messages = array();
				$messages[] = 'Post successfully deleted. <a href="' . base_url() . 'index.php/blog/undelete/' . $id . '/" title="Undo deletion">Undo</a>';
				$this->session->set_userdata('successes', $messages);
				redirect('/', 'blog');
			}
			else
			{
				$messages = array();
				$messages[] = "Error deleting post.";
				$this->session->set_userdata('errors', $messages);
				redirect('/blog/edit/' . $id, 'blog');
			}
		}
		else
		{
			$messages = array();
			$messages[] = "Post could not be deleted because it could not be found";
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');		
		}
	}
	
	public function insertcomment()
	{
		$c = new Blogcomment();
		$p = new Blogpost();
		
		if($p->exists($this->input->post('post_id')))
		{
			$p->get_where(array('id' => $this->input->post('post_id')));
			if($p->commentlocked == "open")
			{
				$c->post_id = $p->id;
				$c->blog_id = $this->blog_id;
				$c->author = $this->input->post('author');
				$c->email = $this->input->post('email');
				$c->website = $this->input->post('website');
				$c->date = $this->input->post('date');
				$c->markdown = $this->input->post('body');
				$c->body = markdown($this->input->post('body'));
				
				$c->ip = $_SERVER['REMOTE_ADDR'];
				
				if($c->save())
				{
					$messages = array();
					$messages[] = "Successfully added comment";
					$this->session->set_userdata('successes', $messages);
					redirect('/blog/comments/' . $c->post_id, 'blog');
				}
				else
				{
					$this->data['author'] = $this->input->post('author');
					$this->data['email'] = $this->input->post('email');
					$this->data['website'] = $this->input->post('website');
					$this->data['body'] = $this->input->post('body');
					
					$messages = array();
					$messages[] = "Error adding comment.";
					$messages[] = $c->error->string;
					$this->session->set_userdata('errors', $messages);
					redirect('/blog/comments/' . $id, 'blog');
				}
			}
			else
			{
				$messages = array();
				$messages[] = "Comments are locked on this entry";
				$this->session->set_userdata('notices', $messages);
				redirect('/blog/comments/' . $id, 'blog');
			}
		}
		else
		{
			$messages = array();
			$messages[] = "Post could not be found";
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');
		}
	}
	
	public function addcomment($id)
	{
		$p = new Blogpost();
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));
			
			if($p->commentlocked == "open")
			{
				$this->data['title'] = "Add comment for " . $p->title;
				$this->data['post'] = $p;
			
				$c = new Blogcomment();
				$c->where('post_id = ' . $p->id);
				$c->order_by('date', 'asc');
				$c->get();
		
				$this->data['comments'] = $c->all;
			
				$this->load->view("add_comment_view", $this->data);
			}
			else
			{
				$messages = array();
				$messages[] = "Comments are locked on this entry";
				$this->session->set_userdata('notices', $messages);
				redirect('/blog/comments/' . $id, 'blog');
			}
		}
		else
		{
			$messages = array();
			$messages[] = "Post could not be found";
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');
		}
	}
	
	public function comments($id, $function="")
	{
		if($function == "add")
		{
			$this->addcomment($id);
		}
		else
		{		
			$this->data['title'] = "View Comments for ";
			$p = new Blogpost();
				
			if($p->exists($id) > 0)
			{
				$p->get_where(array('id' => $id));
				$this->data['post'] = $p;
			
				$c = new Blogcomment();
				$c->where('post_id = ' . $p->id);
				$c->order_by('date', 'asc');
				$c->get();
			
				$this->data['comments'] = $c->all;
				$this->load->view('comment_view', $this->data);
			}
			else
			{
				$messages = array();
				$messages[] = "Post could not be found";
				$this->session->set_userdata('errors', $messages);
				redirect('/', 'blog');
			}
		}
	}
	
	public function editpost($id)
	{
		$this->data['title'] = "Blog - Edit";
		
		if(!$this->session->userdata('loggedin'))
		{
			$messages = array();
			$messages[] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to modify an entry.';
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');		
		}
		else
		{
			$post = new Blogpost();
			
			if($post->exists($id))
			{
				$post->get_where(array('id' => $id));
			
				$u = new Bloguser();
				$u->get_by_username($this->session->userdata('username'));
		
				if(!$u->exists())
				{
					$messages = array();
					$messages[] = 'Something extremely wonky has happened. You appear to be logged in but your username could not be found. Please email me at Josh.Kehn@gmail.com to resolve this issue.';
					$this->session->set_userdata('errors', $messages);
					redirect('/', 'blog');	
				}
				else
				{
					$post->user_id = $u->id;
					$post->blog_id = $this->blog_id;
					$post->title = htmlentities($this->input->post('title'));
					$post->body = markdown($this->input->post('body'));
					$post->markdown = $this->input->post('body');
					
					// Process tags
					
					$tmp = explode(' ', $this->input->post('tags'));
					$tags = '';
					
					foreach($tmp as $tag)
					{
						$tags .= str_replace(',', '', $tag) . ' ';
					}
					
					$post->tags = trim($tags);
					
					$post->date = $this->input->post('date');
				
					if($post->save())
					{
						$messages = array();
						$messages[] = 'Successfully edited ' . $post->title . '.';
						$this->session->set_userdata('successes', $messages);
						redirect('/', 'blog');
					}
					else
					{
						$this->data['messages']['errors'][] = 'Error editing post.';
						$this->data['messages']['errors'][] = $post->error->string;
						$this->data['post'] = $post;
						
						$messages = array();
						$messages[] = "Error editing post";
						$messages[] = $post->error->string;
						$this->session->set_userdata('errors', $messages);
						
						$this->load->view('post_edit_view', $this->data);
					}
				}
			}
			else
			{
				$messages = array();
				$messages[] = "No post found to edit";
				$this->session->set_userdata('errors', $messages);
				redirect('/', 'blog');
			}
		}
	}
	
	public function edit($id)
	{
		if(!$this->session->userdata('loggedin'))
		{
			$messages = array();
			$messages[] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to modify an entry.';
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'view');		
		}
		
		$this->data['title'] = "Edit Post";
		$p = new Blogpost();
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));

			$this->data['post'] = $p;
			
			$this->data['title'] .= ' - ' . $p->title;
			$this->load->view('post_edit_view', $this->data);
		}
		else
		{
			$messages = array();
			$messages[] = "No post by that id found.";
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'blog');
		}
	}
	
	public function view($id)
	{
		$this->data['title'] = "View Post";
		$p = new Blogpost();
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));

			$this->data['post'] = $p;
			
			$this->data['title'] .= ' - ' . $p->title;
			$c = new Blogcomment();
			
			$c->where('post_id = ' . $p->id);
			$this->data['commentcount'] = $c->count();
		}
		else
		{
			$this->data['messages']['errors'][] = "No post by that id found.";
		}
		$this->load->view('post_view', $this->data);
	}
	
	public function insert()
	{
		$this->data['title'] = "Blog - Insert";
		if($this->session->userdata('loggedin'))
		{
			$this->load->view("insert_view", $this->data);
		}
		else
		{
			$messages = array();
			$messages[] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to create an entry.';
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'view');		
		}
	}
	
	public function insertpost()
	{
		$this->data['title'] = "Blog - Insert";
		
		if(!$this->session->userdata('loggedin'))
		{
			$messages = array();
			$messages[] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to create an entry.';
			$this->session->set_userdata('errors', $messages);
			redirect('/', 'view');		
		}
		else
		{
			$post = new Blogpost();
			$u = new Bloguser();
			$u->get_by_username($this->session->userdata('username'));
		
			if(!$u->exists())
			{
				$messages = array();
				$messages[] = 'Something extremely wonky has happened. You appear to be logged in but your username could not be found. Please email me at Josh.Kehn@gmail.com to resolve this issue.';
				$this->session->set_userdata('errors', $messages);
				redirect('/', 'blog');	
			}
			else
			{
				$post->user_id = $u->id;
				$post->blog_id = $this->blog_id;
				$post->title = htmlentities($this->input->post('title'));
				$post->body = markdown($this->input->post('body'));
				$post->markdown = $this->input->post('body');
				
				//Process tags
				
				$tmp = explode(' ', $this->input->post('tags'));
				$tags = '';
				
				foreach($tmp as $tag)
				{
					$tags .= str_replace(',', '', $tag) . ' ';
				}
				
				$post->tags = trim($tags);
				
				
				$post->date = $this->input->post('date');
				
				if($post->save())
				{
					$messages = array();
					$messages[] = 'Successfully inserted ' . $post->title . '.';
					$this->session->set_userdata('success', $messages);
					redirect('/blog/view/' . $post->id, 'blog');	
					
				}
				else
				{
					$messages = array();
					$messages[] = 'Error inserting post.';
					$messages[] = $post->error->string;
					$this->session->set_userdata('errors', $messages);
					
					$this->data['post_title'] = $this->input->post('title');
					$this->data['post_body'] = $this->input->post('body');
					$this->load->view('insert_view', $this->data);
				}
			}
		}
	}
	
	public function login()
	{
		$this->data['title'] = "Blog - Login";
		if($this->input->post('post') and $this->input->post('username') and $this->input->post('password'))
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			$u = new Bloguser();
			$u->get_by_username($username);
			
			if($u->exists())
			{
				if($u->password == $password)
				{
					$u->lastlogin = date("m-d-Y H:i");
					$u->save();
					$arr = array(
							'username' => $u->username,
							'firstname' => $u->firstname,
							'lastname' => $u->lastname,
							'loggedin' => TRUE
						);
					$this->session->set_userdata($arr);
					
					$messages = array();
					$messages[] = 'Successfully logged in.';
					$this->session->set_userdata('successes', $messages);
					redirect('/', 'view');		
				}
				else
				{
					$messages = array();
					$messages[] = 'Incorrect username or password.';				
					$this->session->set_userdata('errors', $messages);
					$this->load->view('login_view', $this->data);					
				}
			}
			else
			{
				$messages = array();
				$messages[] = 'Incorrect username or password.';				
				$this->session->set_userdata('errors', $messages);
				$this->load->view('login_view', $this->data);					
			}
		}
		else if($this->input->post('post'))
		{
			$messages = array();
			$messages[] = 'Please fill out all fields.';
			$this->session->set_userdata('errors', $messages);
			$this->load->view('login_view', $this->data);					
		}
		else
		{
			$this->load->view('login_view', $this->data);
		}
	}
	public function logout()
	{
		$arr = array(
				'username' => '',
				'firstname' => '',
				'lastname' => '',
				'loggedin' => FALSE
			);
		
		$this->session->unset_userdata($arr);
		$messages = array();
		$messages[] = 'Successfully logged out.';				
		$this->session->set_userdata('successes', $messages);
		redirect('/blog/login');
	}
}
/* End of file blog.php */
/* Location: ./system/application/controller/blog.php */