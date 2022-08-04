import { __ } from '@wordpress/i18n';
import { PlainText, useBlockProps } from '@wordpress/block-editor';
 
export default function Edit( { attributes, setAttributes } ) {
    
	const inputId = 'library-shortcode-input-bl';
    let short_code_lebel = __( 'Library Shortcode: ' ) + '[bl_search_library]';
    return (
		<div { ...useBlockProps( { className: 'components-placeholder' } ) }>
			<label
				htmlFor={ inputId }
				className="components-placeholder__label"
			>

				{ short_code_lebel }
			</label>
			<PlainText
				className="block-shortcode__textarea"
				id={ inputId }
				value={ attributes.text }
				aria-label={ __( 'Library shortcode text' ) }
				placeholder={ __( 'Enter shortcode' ) }
				onChange={ ( text ) => setAttributes( { text } ) }
			/>
		</div>
	);
}
 