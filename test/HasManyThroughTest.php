<?php
include 'helpers/foo.php';

use foo\bar\biz\User;
use foo\bar\biz\Newsletter;
use foo\bar\biz\Story;

class HasManyThroughTest extends DatabaseTest
{
	public function test_gh101_has_many_through()
	{
		$user = User::find(1);
		$newsletter = Newsletter::find(1);

		$this->assert_equals($newsletter->id, $user->newsletters[0]->id);
		$this->assert_equals(
			'foo\bar\biz\Newsletter',
			get_class($user->newsletters[0])
		);
		$this->assert_equals($user->id, $newsletter->users[0]->id);
		$this->assert_equals(
			'foo\bar\biz\User',
			get_class($newsletter->users[0])
		);
	}

	public function test_gh101_has_many_through_include()
	{
		$user = User::find(1, array(
			'include' => array(
				'user_newsletters'
			)
		));

		$this->assert_equals(1, $user->id);
		$this->assert_equals(1, $user->user_newsletters[0]->id);
	}

	public function test_gh107_has_many_through_include_eager()
	{
		$venue = Venue::find(1, array('include' => array('events')));
		$this->assert_equals(1, $venue->events[0]->id);

		$venue = Venue::find(1, array('include' => array('hosts')));
		$this->assert_equals(1, $venue->hosts[0]->id);
	}

	public function test_gh107_has_many_through_include_eager_with_namespace()
	{
		$user = User::find(1, array(
			'include' => array(
				'newsletters'
			)
		));

		$this->assert_equals(1, $user->id);
		$this->assert_equals(1, $user->newsletters[0]->id);
	}

  /**
   * @see https://github.com/zamzar/php-activerecord/issues/4
   */
  public function test_has_many_through_does_not_require_bidi_relationship() {
    $first_story = Story::find(1);
    $this->assertEquals([User::find(1), User::find(3)], $first_story->readers);

    $second_story = Story::find(2);
    $this->assertEquals([User::find(2)], $second_story->readers);
  }

	/**
   * @see https://github.com/zamzar/php-activerecord/issues/4
   */
  public function test_has_many_through_does_not_require_bidi_relationship_eager_load() {
    $first_story = Story::find(1, array('include' => array('readers')));
    $this->assertEquals([User::find(1), User::find(3)], $first_story->readers);

    $second_story = Story::find(2, array('include' => array('readers')));
    $this->assertEquals([User::find(2)], $second_story->readers);
  }
}
