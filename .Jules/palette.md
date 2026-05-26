## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-05-26 - Proper visual hiding of inputs for focus
**Learning:** Using `display: none` on native checkboxes removes them from the accessibility tree and makes them unfocusable via keyboard. Relying purely on hover classes for revealing interactive elements hides them from keyboard users.
**Action:** Use visually hidden styles (`opacity: 0; position: absolute; width: 1px; height: 1px;`) instead of `display: none` so the input remains in the tab order, and apply `:focus-visible` styling to sibling elements to show the focus ring. Combine hover states with focus-within or focus states to ensure keyboard navigability.
