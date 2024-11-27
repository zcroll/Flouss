import { cva, type VariantProps } from 'class-variance-authority'

export { default as Sheet } from './Sheet.vue'
export { default as SheetTrigger } from './SheetTrigger.vue'
export { default as SheetContent } from './SheetContent.vue'
export { default as SheetHeader } from './SheetHeader.vue'
export { default as SheetTitle } from './SheetTitle.vue'

export const sheetVariants = cva(
  'yesfixed yesz-50 yesgap-4 yesbg-background yesp-6 yesshadow-lg yestransition yesease-in-out data-[state=open]:yesanimate-in data-[state=closed]:yesanimate-out data-[state=closed]:yesduration-300 data-[state=open]:yesduration-500',
  {
    variants: {
      side: {
        top: 'yesinset-x-0 yestop-0 yesborder-b data-[state=closed]:yesslide-out-to-top data-[state=open]:yesslide-in-from-top',
        bottom:
            'yesinset-x-0 yesbottom-0 yesborder-t data-[state=closed]:yesslide-out-to-bottom data-[state=open]:yesslide-in-from-bottom',
        left: 'yesinset-y-0 yesleft-0 yesh-full yesw-3/4 yesborder-r data-[state=closed]:yesslide-out-to-left data-[state=open]:yesslide-in-from-left sm:yesmax-w-sm',
        right:
            'yesinset-y-0 yesright-0 yesh-full yesw-3/4 yesborder-l data-[state=closed]:yesslide-out-to-right data-[state=open]:yesslide-in-from-right sm:yesmax-w-sm',
      },
    },
    defaultVariants: {
      side: 'right',
    },
  },
)

export type SheetVariants = VariantProps<typeof sheetVariants>
