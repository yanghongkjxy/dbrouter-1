<?php namespace Dbrouter\Database\Mapper;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url segment parser item test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class SegmentTypeMapperTest extends PHPUnit_Framework_TestCase
{
    protected $db = NULL;

    public function setUp()
    {
        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->name = 'path';

	$row2 = new \stdClass();
        $row2->id   = 2;
        $row2->name = 'file';

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->name = 'wildcard';

        $this->db = m::mock('Doctrine\DBAL\Connection');
        $this->db->shouldReceive('setFetchMode');
        $this->db->shouldReceive('fetchAll')->andReturn(array($row1, $row2, $row3));
    }

    /**
     * @expectedException Dbrouter\Exception\Database\MapperException
     */
    public function testLoadException()
    {
        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('setFetchMode');
        $db->shouldReceive('fetchAll')->andReturn(array());

        $mapper = new SegmentTypeMapper($db);
    }

    public function testNewMapper()
    {
        $mapper = new SegmentTypeMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\SegmentTypeMapper', $mapper);
        $this->assertEquals(1, $mapper->getValue('path'));
        $this->assertEquals(2, $mapper->getValue('file'));
        $this->assertEquals(3, $mapper->getValue('wildcard'));

        // Placeholder not mapped
        $this->assertEmpty($mapper->getValue('placeholder'));
    }

    public function testGetTypeID()
    {
        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('getType')->once()->andReturn('path');

        $mapper = new SegmentTypeMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\SegmentTypeMapper', $mapper);
        $this->assertEquals(1, $mapper->getTypeId($item));

    }

}