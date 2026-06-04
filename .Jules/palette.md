## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-20 - Keyboard Accessibility for Custom Checkboxes and Role Buttons
**Learning:** Using `display: none` on a native `<input>` element inside a custom checkbox component breaks keyboard focusability, making the element entirely invisible to screen reader and keyboard users. Additionally, elements with `role="button"` (like `<p>`) must explicitly include `tabindex="0"` and handle `keydown.enter` / `keydown.space` events to be truly accessible.
**Action:** When building custom checkboxes, hide the native input visually (using `opacity: 0; position: absolute; width: 1px; height: 1px;`) and use its `:focus-visible` state to apply a focus ring to the custom sibling element. Always ensure `role="button"` elements are focusable and handle keyboard activation.
