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
        adminViewAccountHeaderColor: '#F9BEBE',
        adminViewAccountHeaderColor2: '#E5EFF5',
        declineButton: '#FFD65F',
        approveButton: '#B2E987',
        submitButton: '#D8345F',
        multiStepBoxColor: '#C8C8C8',
        sundayToSaturdayBoxColor: '#C4C4C4',
        manageRestaurantSidebarColor: '#7A7A7A',
        manageRestaurantHrColor: '#484848',
        manageRestaurantSidebarColorActive: '#588DA8',
        manageFoodItemHeaderBgColor: '#F8F8F8',
        loginLandingPage: '#387BAB',
        lightBlue: '#C8DDEA',
        postedStatus: '#0AA034',
        completeColor: '#FBBC05',
        btnHoverColor: '#B02B4E',
      },
      width: {
        adminLoginBoxW: '800px',
        adminDashboardBox: '900px',
        addFoodItemModalW: '500px',
      },
      height: {
        adminLoginBoxH: '527px',
        adminLoginBoxH2: '800px'
      },
      fontSize: {
        circle: '0.50rem'
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
        Roboto: ['Roboto', 'sans-serif']
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
