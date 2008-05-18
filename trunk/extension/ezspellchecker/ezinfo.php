<?php
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
//

class ezspellcheckerInfo
{
    static function info()
    {
        return array( 'Name' => "eZ Spellchecker extension",
                      'Version' => "0.2.0",
                      'Copyright' => "Copyright (C) 1999-2008 eZ systems AS",
                      'License' => "GNU General Public License v2.0",
                      'Includes the following third-party software' => array( 'Name' => 'DHTML Spell Checker',
                                                                              'Version' => '1.0',
                                                                              'License' => 'MIT License - 2005 - Emil A Eklund',
                                                                              'For more information' => 'http://eae.net/contact/emil' )
                      );
    }
}
?>
