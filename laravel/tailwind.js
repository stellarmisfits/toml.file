module.exports = {
  theme: {
    customForms: theme => ({
      default: {
        'input, textarea, multiselect, select': {
          marginTop: theme('spacing.1'),
          width: theme('width.full'),
          borderRadius: theme('borderRadius.default'),
          borderColor: theme('colors.gray.300'),
          backgroundColor: theme('colors.white'),
          '&:focus': {
            backgroundColor: theme('colors.white')
          }
        },
        checkbox: {
          borderColor: theme('colors.gray.300')
        }
      }
    }),
    // https://github.com/benface/tailwindcss-transforms
    scale: { // defaults to {}
      '75': '0.75',
      '100': '1'
    },
    // https://github.com/benface/tailwindcss-transitions
    transitionDuration: {
      'default': '50ms',
      '0': '0ms',
      '25': '25ms',
      '50': '50ms',
      '100': '100ms'
    },
    extend: {
      maxHeight: {
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%'
      },
      fontFamily: {
        sans: [
          'Nunito',
          'Ubuntu',
          '-apple-system',
          'BlinkMacSystemFont',
          '"Segoe UI"',
          'Roboto',
          '"Helvetica Neue"',
          'Arial',
          '"Noto Sans"',
          'sans-serif',
          '"Apple Color Emoji"',
          '"Segoe UI Emoji"',
          '"Segoe UI Symbol"',
          '"Noto Color Emoji"'
        ]
      },
      spacing: {
        '72': '18rem',
        '80': '20rem',
        '96': '24rem',
        '128': '32rem'
      },
      zIndex: {
        '100': '100'
      }
    }
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms'),
    require('tailwindcss-transitions')(),
    require('tailwindcss-transforms')({
      '3d': false, // defaults to false
    })
  ]
}
