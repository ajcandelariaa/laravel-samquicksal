module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        headerBgColor: '#E58A8A',
        headerActiveTextColor: '#D8345F',
        sideBarHoverBgColor: '#343434',
        sideBarBgColor: '#484848',
        tableBgHeader: '#FFE2E2',
        adminLoginTextColor: '#588DA8',
        darkeradminLoginTextColor: '#5288a3',
        adminViewAccountHeaderColor: '#F9BEBE',
        adminViewAccountHeaderColor2: '#E5EFF5',
        declineButton: '#FFD65F',
        approveButton: '#B2E987',
        submitButton: '#D8345F',
        darkerSubmitButton: '#B8234A',
        multiStepBoxColor: '#C8C8C8',
        sundayToSaturdayBoxColor: '#C4C4C4',
        manageRestaurantSidebarColor: '#7A7A7A',
        hoverManageRestaurantSidebarColor:'#6d6d6d',
        manageRestaurantHrColor: '#484848',
        manageRestaurantSidebarColorActive: '#588DA8',
        hoverManageRestaurantSidebarColorActive : '#3a5d6f',
        manageFoodItemHeaderBgColor: '#F8F8F8',
        loginLandingPage: '#387BAB',
        lightBlue: '#C8DDEA',
        postedStatus: '#0AA034',
        completeColor: '#FBBC05',
        btnHoverColor: '#B02B4E',
        adminBgColor: '#E5E5E5',
        adminDeleteFormColor: '#91001B',
        boxPeach: '#F2B4B4',
        fadedPink: '#efb8b8',
      },
      width: {
        adminLoginBoxW: '800px',
        adminDashboardBox: '900px',
        addFoodItemModalW: '500px',
      },
      height: {
        adminLoginBoxH: '527px',
        adminLoginBoxH2: '800px',
        height1Px: '1px',
      },
      fontSize: {
        circle: '0.50rem',
        xxs: '0.60rem',
      },
      animation:{
        spin: 'spin 6s linear infinite',
        wiggle: 'wiggle 1s ease-in-out infinite',
        blob: 'blob 7s infinite',
      },
      gridTemplateColumns: {
        adminViewAccountGridSize: '120px minmax(120px, 1fr) auto',
        foodSet: '180px auto',
        postGrid: '200px auto 150px',
        formsThreeCols: '200px 80px auto',
        profileHeaderGrid: '220px auto',
        adminDashboardThreeCols: '50px 50px auto',
        storeHoursThreeCols: '100px 50px 200px',
        unavailableDatesGrid: '100px auto 40px',
        policyGrid: '40px auto 120px',
        stampCardGrid: '150px 50px auto',
        checkListGrid: '180px 350px auto',
        checkListGrid2: '70px auto',
        customerDetailsGrid: '200px auto auto',
        orderCustDetailsGrid: '100px auto',
      },
      gridTemplateRows:{
        customerOrderingTableRow: '20px auto 50px',
      },
      boxShadow: {
        adminDownloadButton: '0px 3px 14px -9px rgba(0,0,0,0.75)'
      },
      borderWidth: {
        multiStepBoxBorder: '1px'
      },
      inset: {
        headerLogoLeftMargin: '10%'
      },
      fontFamily:{
        Montserrat: ['Montserrat', 'sans-serif'],
        Roboto: ['Roboto', 'sans-serif'],
        Molle: ['Molle', 'sans-serif'],
        Raleway: ['Raleway', 'sans-serif'],
      },
      keyframes: {
        blob: {
        '0%': { transform: 'translate(0px, 0px) scale(1)'},
        '33%': { transform: 'translate(30px, -50px) scale(1.1)'},
        '66%': { transform: 'translate(-20px, 20px) scale(0.9)'},
        '100%': { transform: 'translate(0px, 0px) scale(1)'},

        },
        wiggle: {
          '0%, 100%': { transform: 'rotate(-6deg)' },
          '50%': { transform: 'rotate(6deg)' },
        },
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
