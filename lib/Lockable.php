<?php
/*!
 * \file Lockable.php
 * \interface  Lockable
 * \brief when an object implements Lockable it may be unmutable. The developper must ensure the unmutability of the object
 whenever lock is called. Static method are implemented on the final class Lockables (to send LockException for instance).
 This has been used during developement but is not anymore. However it might be useful for some case to have unmutable object to ensure
 data integrity.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
interface Lockable
{
	public function lock();
	public function isLocked();
}

/*!
 * \file Lockable.php
 * \class Lockables
 * \brief Collection of methods that can be used with objects that implements Lockable.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
final class Lockables
{
	/*! 
      *  \brief Throw a LockException
      */
	public final static function sendLockException($sender)
	{
		throw new Exception(get_class($sender)."'s object is locked.'");
	}
}
?>
