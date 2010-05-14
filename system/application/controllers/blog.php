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
		
		if($_SERVER['DOCUMENT_ROOT'] == '/Users/benaroia')
		{
			$this->output->enable_profiler(TRUE);
		}
	}
	
	public function index()
	{
		$this->data['title'] = "Home";
		
		$b = new Blogmodel();
		$b->get($this->blog_id);
		
		$p = new Blogpost();
		
//		if($p->where('blogid = ' . $b->id)->count() > 0)
		{
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
				$this->data['messages']['notices'][] = "Post flagging not enabled at this time.";
				$this->index();
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
					$this->comments($c->postid);
				}
				else
				{
					if($f->save() and $c->save())
					{
						$this->data['messages']['success'][] = "Comment flagged successfully.";
						$this->comments($c->postid);
					}
					else
					{
						$this->data['messages']['errors'][] = "Error flagging comment";
						$this->data['messages']['errors'][] = $c->error->string;
						$this->data['messages']['errors'][] = $f->error->string;
						$this->comments($c->postid);
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
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));
			
			$p->deleted = "false";
			
			if($p->save())
			{
				$this->data['messages']['success'][] = 'Post successfully undeleted.';
				$this->index();
			}
			else
			{
				$this->data['messages']['errors'][] = "Error undeleting post.";
				$this->data['messages']['errors'][] = $p->error->string;
				$this->edit($id);
			}
		}
		else
		{
			$this->data['messages']['errors'][] = "Post could not be undeleted because it could not be found.";
			$this->index();
		}
	}
	
	public function delete($id)
	{
		$p = new Blogpost();
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));
			
			$p->deleted = "true";
			
			if($p->save())
			{
				$this->data['messages']['success'][] = 'Post successfully deleted. <a href="' . base_url() . 'index.php/blog/undelete/' . $id . '/" title="Undo deletion">Undo</a>';
				$this->index();
			}
			else
			{
				$this->data['messages']['errors'][] = "Error deleting post.";
				$this->data['messages']['errors'][] = $p->error->string;
				$this->edit($id);
			}
		}
		else
		{
			$this->data['messages']['errors'][] = "Post could not be deleted because it could not be found.";
			$this->index();
		}
	}
	
	public function insertcomment()
	{
		$c = new Blogcomment();
		$p = new Blogpost();
		
		if($p->exists($this->input->post('postid')))
		{
			$p->get_where(array('id' => $this->input->post('postid')));
			if($p->commentlocked == "open")
			{
				$c->postid = $p->id;
				$c->blogid = $this->blog_id;
				$c->author = $this->input->post('author');
				$c->email = $this->input->post('email');
				$c->website = $this->input->post('website');
				$c->date = $this->input->post('date');
				$c->markdown = $this->input->post('body');
				$c->body = markdown($this->input->post('body'));
				
				$c->ip = $_SERVER['REMOTE_ADDR'];
				
				if($c->save())
				{
					$this->data['messages']['success'][] = "Successfully added comment";
					$this->comments($c->postid);
				}
				else
				{
					$this->data['messages']['errors'][] = $c->error->string;
					$this->data['author'] = $this->input->post('author');
					$this->data['email'] = $this->input->post('email');
					$this->data['website'] = $this->input->post('website');
					$this->data['body'] = $this->input->post('body');
					$this->addcomment($c->postid);
				}
			}
			else
			{
				$this->data['messages']['notices'][] = "Comments are locked on this entry.";
				$this->view($this->input->post('postid'));
			}
		}
		else
		{
			$this->data['messages']['notices'][] = "Post could not be found.";
			$this->index();
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
				$c->where('postid = ' . $p->id);
				$c->order_by('date', 'asc');
				$c->get();
		
				$this->data['comments'] = $c->all;
			
				$this->load->view("add_comment_view", $this->data);
			}
			else
			{
				$this->data['messages']['notices'][] = "Comments are locked on this entry.";
				$this->comments($id);
			}
		}
		else
		{
			echo("Doesn't exist");
			$this->data['messeges']['errors'][] = "No post by that id was found.";
			$this->view($id);
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
				$c->where('postid = ' . $p->id);
				$c->order_by('date', 'asc');
				$c->get();
			
				$this->data['comments'] = $c->all;
			}
			else
			{
				$this->data['title'] .= "None";
				$this->data['messages']['errors'][] = "No comments were found because that post could not be found.";
			}
			$this->load->view('comment_view', $this->data);
		}
	}
	
	public function editpost($id)
	{
		$this->data['title'] = "Blog - Edit";
		
		if(!$this->session->userdata('loggedin'))
		{
			$this->data['messages']['errors'][] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to create an entry.';
			$this->index();			
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
					$this->data['messages']['errors'][] = 'Something extremely wonky has happened. You appear to be logged in but your username could not be found. Please email me at Josh.Kehn@gmail.com to resolve this issue.';
					$this->index();			
				}
				else
				{
					$post->userid = $u->id;
					$post->blogid = $this->blog_id;
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
						$this->data['messages']['success'][] = 'Successfully edited ' . $post->title . '.';
						$this->view($post->id);
					}
					else
					{
						$this->data['messages']['errors'][] = 'Error editing post.';
						$this->data['messages']['errors'][] = $post->error->string;
						$this->data['post'] = $post;
					
						$this->load->view('post_edit_view', $this->data);
					}
				}
			}
			else
			{
				$this->data['messages']['errors'][] = 'No post found to edit.';
				$this->data['post'] = $post;
			
				$this->load->view('post_edit_view', $this->data);
			}
		}
	}
	
	public function edit($id)
	{
		$this->data['title'] = "Edit Post";
		$p = new Blogpost();
		
		if($p->exists($id))
		{
			$p->get_where(array('id' => $id));

			$this->data['post'] = $p;
			
			$this->data['title'] .= ' - ' . $p->title;
		}
		else
		{
			$this->data['messages']['errors'][] = "No post by that id found.";
		}
		$this->load->view('post_edit_view', $this->data);
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
			
			$c->where('postid = ' . $p->id);
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
			$this->data['messages']['errors'][] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to create an entry.';
			$this->index();			
		}
	}
	
	public function insertpost()
	{
		$this->data['title'] = "Blog - Insert";
		
		if(!$this->session->userdata('loggedin'))
		{
			$this->data['messages']['errors'][] = 'Please <a href="' . base_url() . 'index.php/blog/login" title="Login">login</a> before attempting to create an entry.';
			$this->index();			
		}
		else
		{
			$post = new Blogpost();
			$u = new Bloguser();
			$u->get_by_username($this->session->userdata('username'));
		
			if(!$u->exists())
			{
				$this->data['messages']['errors'][] = 'Something extremely wonky has happened. You appear to be logged in but your username could not be found. Please email me at Josh.Kehn@gmail.com to resolve this issue.';
				$this->index();			
			}
			else
			{
				$post->userid = $u->id;
				$post->blogid = $this->blog_id;
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
					$this->data['messages']['success'][] = 'Successfully inserted ' . $post->title . '.';
					$this->view($post->id);
				}
				else
				{
					$this->data['messages']['errors'][] = 'Error inserting post.';
					$this->data['messages']['errors'][] = $post->error->string;
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
					
					$this->data['messages']['success'][] = "Successfully logged in as ".$u->username;
					 
					$this->index();
				}
				else
				{
					$this->data['errors'][] = "Incorrect username or password.";				
					$this->load->view('login_view', $this->data);					
				}
			}
			else
			{
				$this->data['errors'][] = "Incorrect username or password.";				
				$this->load->view('login_view', $this->data);
			}
		}
		else if($this->input->post('post'))
		{
			$this->data['errors'][] = "Please fill out all fields.";
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
		$this->data['messages']['success'][] = "Successfully logged out.";
		$this->login();
	}
}
/* End of file blog.php */
/* Location: ./system/application/controller/blog.php */