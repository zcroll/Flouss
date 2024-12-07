import { cva, type VariantProps } from 'class-variance-authority'

export { default as Button } from './Button.vue'

export const buttonVariants = cva(
  'yesinline-flex yesitems-center yesjustify-center yesgap-2 yeswhitespace-nowrap yesrounded-md yestext-sm yesfont-medium yesring-offset-background yestransition-colors focus-visible:yesoutline-none focus-visible:yesring-2 focus-visible:yesring-ring focus-visible:yesring-offset-2 disabled:yespointer-events-none disabled:yesopacity-50 [&_svg]:yespointer-events-none [&_svg]:yessize-4 [&_svg]:yesshrink-0',
  {
    variants: {
      variant: {
        default:
          'yesbg-primary yestext-primary-foreground yesshadow hover:yesbg-primary/90',
        destructive:
          'yesbg-destructive yestext-destructive-foreground yesshadow-sm hover:yesbg-destructive/90',
        outline:
          'yesborder yesborder-input yesbg-background yesshadow-sm hover:yesbg-accent hover:yestext-accent-foreground',
        secondary:
          'yesbg-secondary yestext-secondary-foreground yesshadow-sm hover:yesbg-secondary/80',
        ghost: 'hover:yesbg-accent hover:yestext-accent-foreground',
        link: 'yestext-primary yesunderline-offset-4 hover:yesunderline',
      },
      size: {
        default: 'yesh-9 yespx-4 yespy-2',
        sm: 'yesh-8 yesrounded-md yespx-3 yestext-xs',
        lg: 'yesh-10 yesrounded-md yespx-8',
        icon: 'yesh-9 yesw-9',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  },
)

export type ButtonVariants = VariantProps<typeof buttonVariants>
