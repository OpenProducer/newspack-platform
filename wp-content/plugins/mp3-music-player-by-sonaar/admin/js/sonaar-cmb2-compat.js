/** Sonaar group sorting support for the unmodified CMB2 distribution. */
( function( $, cmb ) {
    'use strict';

    if ( ! cmb || ! cmb.updateNameAttr ) {
        return;
    }

    // Conditional fields can be hidden when WordPress initializes CodeMirror.
    // Refresh them when revealed so the editor does not stay blank.
    var codeEditorArgs = cmb.codeEditorArgs;
    cmb.codeEditorArgs = function( overrides ) {
        var args = codeEditorArgs( overrides );
        args.codemirror = $.extend( {}, args.codemirror, { autoRefresh: true } );
        return args;
    };

    // CMB2 updates names; Sonaar's selectors and conditional fields also use IDs
    // and row classes containing the group index.
    var updateNameAttr = cmb.updateNameAttr;
    cmb.updateNameAttr = function( $field, previous, next ) {
        var id = $field.attr( 'id' );
        if ( $field.attr( 'name' ) && id ) {
            updateNameAttr( $field, previous, next );
        }
        if ( id ) {
            var newId = id.replace( '_' + previous + '_', '_' + next + '_' );
            $field.attr( 'id', newId );
            $field.closest( '.cmb-repeatable-grouping' ).find( 'label' ).filter( function() {
                return $( this ).attr( 'for' ) === id;
            } ).attr( 'for', newId );
        }
        updateRowClasses( $field.closest( '.cmb-repeatable-grouping' ), previous, next );
    };

    var elReplacements = cmb.elReplacements;
    cmb.elReplacements = function( $field, previous, group ) {
        var result = elReplacements( $field, previous, group );
        if ( group ) {
            updateRowClasses( $field.closest( '.cmb-repeatable-grouping' ), previous, cmb.idNumber );
        }
        return result;
    };

    function updateRowClasses( $row, previous, next ) {
        $row.find( '.cmb-repeat-group-field' ).each( function() {
            this.className = this.className.replace( '-' + previous + '-', '-' + next + '-' );
        } );
    }

    var resetTitlesAndIterator = cmb.resetTitlesAndIterator;
    cmb.resetTitlesAndIterator = function( event ) {
        if ( event.group ) {
            $( event.target ).find( '.cmb-repeatable-grouping' ).each( function( index ) {
                updateRowClasses( $( this ), $( this ).data( 'iterator' ), index );
            } );
        }
        return resetTitlesAndIterator( event );
    };

    // Track titles contain Sonaar's preview controls and must survive sorting.
    var resetGroupTitles = cmb.resetGroupTitles;
    cmb.resetGroupTitles = function( $row, index, title ) {
        if ( $row.closest( '[data-groupid]' ).data( 'groupid' ) !== 'alb_tracklist' ) {
            resetGroupTitles( $row, index, title );
        } else {
            // Renumber only the prefix; keep the filename and preview markup.
            $row.find( 'h3.cmb-group-title' ).first().contents().filter( function() {
                return this.nodeType === 3;
            } ).first().each( function() {
                this.nodeValue = this.nodeValue.replace( /\d+/, parseInt( index, 10 ) + 1 );
            } );
        }
    };

    function makeGroupsSortable() {
        cmb.metabox().find( '.cmb-repeatable-group.sortable' ).each( function() {
            var $group = $( this );
            if ( $group.data( 'sonaar-sortable' ) ) {
                return;
            }
            $group.data( 'sonaar-sortable', true ).sortable( {
                items: '> .cmb-repeatable-grouping',
                handle: '.cmb-group-title',
                cursor: 'move',
                cancel: 'input,textarea,button,select,option,a,.wp-editor-wrap',
                start: function( event, ui ) {
                    // Save and remove editors before jQuery UI moves their DOM.
                    $group.children( '.cmb-repeatable-grouping' ).each( function() {
                        var $row = $( this );
                        cmb.triggerElement( ui.item, 'cmb2_shift_rows_start', ui.item, $row, $row );
                    } );
                },
                stop: function( event, ui ) {
                    cmb.resetTitlesAndIterator( { group: true, target: $group[0] } );
                    $group.children( '.cmb-repeatable-grouping' ).each( function() {
                        var $row = $( this );
                        cmb.triggerElement( ui.item, 'cmb2_shift_rows_complete', ui.item, $row, $row );
                    } );
                }
            } );
        } );
    }

    $( document ).on( 'cmb_init', makeGroupsSortable );
    $( function() {
        if ( cmb.$metabox ) {
            makeGroupsSortable();
        }
    } );
    $( document ).on( 'cmb2_add_row cmb2_shift_rows_complete', function( event, $row ) {
        cmb.metabox().find( '.cmb-repeatable-grouping[id]' ).each( function() {
            this.id = this.id.replace( /-\d+$/, '-' + $( this ).data( 'iterator' ) );
        } );
        // Keep nested repeatable lists sortable in newly inserted groups too.
        if ( $row && $row.find ) {
            cmb.makeRepeatableSortable();
        }
    } );
} )( jQuery, window.CMB2 );
