import 'package:flutter/cupertino.dart';
import 'package:stackfood_multivendor/common/widgets/custom_snackbar_widget.dart';
import 'package:stackfood_multivendor/common/widgets/custom_tool_tip.dart';
import 'package:stackfood_multivendor/features/auth/controllers/auth_controller.dart';
import 'package:stackfood_multivendor/features/cart/controllers/cart_controller.dart';
import 'package:stackfood_multivendor/features/language/controllers/localization_controller.dart';
import 'package:stackfood_multivendor/features/splash/controllers/splash_controller.dart';
import 'package:stackfood_multivendor/features/splash/controllers/theme_controller.dart';
import 'package:stackfood_multivendor/features/auth/widgets/auth_dialog_widget.dart';
import 'package:stackfood_multivendor/features/location/controllers/location_controller.dart';
import 'package:stackfood_multivendor/helper/address_helper.dart';
import 'package:stackfood_multivendor/helper/auth_helper.dart';
import 'package:stackfood_multivendor/helper/route_helper.dart';
import 'package:stackfood_multivendor/util/app_constants.dart';
import 'package:stackfood_multivendor/util/dimensions.dart';
import 'package:stackfood_multivendor/util/images.dart';
import 'package:stackfood_multivendor/util/styles.dart';
import 'package:stackfood_multivendor/common/widgets/custom_dropdown_widget.dart';
import 'package:stackfood_multivendor/common/widgets/hover_widgets/text_hover_widget.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class WebMenuBar extends StatelessWidget implements PreferredSizeWidget {
  final bool fromDineIn;
  const WebMenuBar({super.key, this.fromDineIn = false});

  @override
  Widget build(BuildContext context) {
    bool isSelectLanguage = false;
    final Color barBg = Theme.of(context).primaryColor;
    const Color barFg = Colors.white;

    return  Column(
      children: [
        Container(
          height: 40, width: double.infinity,
          color: barBg,
          child: Center(
            child: SizedBox(
              width: Dimensions.webMaxWidth,
              child: Row(children: [
                SizedBox(
                  width: 500,
                  child: GetBuilder<LocationController>(builder: (locationController) {
                      return AddressHelper.getAddressFromSharedPref() != null ? InkWell(
                        onTap: () => Get.find<SplashController>().navigateToLocationScreen('home'),
                        child: Padding(
                          padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeSmall),
                          child: GetBuilder<LocationController>(builder: (locationController) {
                            return Row(children: [
                              Icon(
                                AddressHelper.getAddressFromSharedPref()!.addressType == 'home' ? CupertinoIcons.house_alt_fill
                                    : AddressHelper.getAddressFromSharedPref()!.addressType == 'office' ? CupertinoIcons.bag_fill : CupertinoIcons.location_solid,
                                size: 16, color: barFg,
                              ),
                              const SizedBox(width: Dimensions.paddingSizeExtraSmall),

                              Text(
                                '${AddressHelper.getAddressFromSharedPref()!.addressType == 'others' ? 'your_location'.tr : AddressHelper.getAddressFromSharedPref()!.addressType!.tr}: ',
                                style: robotoMedium.copyWith(
                                  color: barFg, fontSize: Dimensions.fontSizeExtraSmall,
                                ),
                                maxLines: 1, overflow: TextOverflow.ellipsis,
                              ),

                              Flexible(
                                child: Text(
                                  AddressHelper.getAddressFromSharedPref()!.address!,
                                  style: robotoRegular.copyWith(
                                    color: barFg.withValues(alpha: 0.92), fontSize: Dimensions.fontSizeExtraSmall,
                                  ),
                                  maxLines: 1, overflow: TextOverflow.ellipsis,
                                ),
                              ),
                              Icon(Icons.keyboard_arrow_down, color: barFg.withValues(alpha: 0.9)),
                            ]);
                          }),
                        ),
                      ) : InkWell(
                        onTap: () {
                          Get.find<SplashController>().navigateToLocationScreen('home');
                        },
                        child: Padding(
                          padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeSmall),
                          child: Row(children: [
                            Icon(
                              CupertinoIcons.location_solid,
                              size: 16, color: barFg,
                            ),
                            const SizedBox(width: Dimensions.paddingSizeExtraSmall),

                            CustomToolTip(
                              message: 'please_setup_your_location_to_view_available_restaurants_and_foods_in_your_area'.tr,
                              preferredDirection: AxisDirection.down,
                              fontSize: Dimensions.fontSizeSmall,
                              isShowOnInit: true,
                              child: InkWell(
                                onTap: () async {
                                  Get.find<SplashController>().navigateToLocationScreen('home');
                                },
                                child: Text(
                                  'select_your_location'.tr,
                                  style: robotoMedium.copyWith(
                                    color: barFg, fontSize: Dimensions.fontSizeExtraSmall,
                                  ),
                                  maxLines: 1, overflow: TextOverflow.ellipsis,
                                ),
                              ),
                            ),
                            Icon(Icons.keyboard_arrow_down, color: barFg.withValues(alpha: 0.9)),
                          ]),
                        ),
                      );
                    }
                  ),
                ),

                const Spacer(),

                GetBuilder<LocalizationController>(builder: (localizationController) {

                  List<DropdownItem<int>> languageList = [];
                  List<DropdownItem<int>> joinUsList = [];

                  for(int index=0; index<AppConstants.languages.length; index++) {
                    languageList.add(DropdownItem<int>(value: index, child: Row(
                      children: [
                        SizedBox(height: 15, width: 15, child: Image.asset(AppConstants.languages[index].imageUrl!)),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 8.0, vertical: 5),
                          child: Text(AppConstants.languages[index].languageName!, style: robotoRegular.copyWith(fontSize: Dimensions.fontSizeExtraSmall),),
                        ),
                      ],
                    )));
                  }

                  if (Get.find<SplashController>().configModel!.toggleRestaurantRegistration!) {
                    joinUsList.add(DropdownItem<int>(
                      value: 1,
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 5.0, vertical: 5),
                        child: Text(
                          AppConstants.joinDropdown[1].tr,
                          style: robotoRegular.copyWith(
                            fontSize: Dimensions.fontSizeExtraSmall,
                            fontWeight: FontWeight.w100,
                            color: Colors.black87,
                          ),
                        ),
                      ),
                    ));
                  }

                  if (Get.find<SplashController>().configModel!.toggleDmRegistration!) {
                    joinUsList.add(DropdownItem<int>(
                      value: 2,
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 5.0, vertical: 5),
                        child: Text(
                          AppConstants.joinDropdown[2].tr,
                          style: robotoRegular.copyWith(
                            fontSize: Dimensions.fontSizeExtraSmall,
                            fontWeight: FontWeight.w100,
                            color: Colors.black87,
                          ),
                        ),
                      ),
                    ));
                  }

                  return Row(children: [
                    SizedBox(
                      width: 120,
                      child: CustomDropdown<int>(
                        onChange: (int? value, int index) {
                          localizationController.setLanguage(Locale(
                            AppConstants.languages[index].languageCode!,
                            AppConstants.languages[index].countryCode,
                          ));
                          localizationController.setSelectLanguageIndex(index);
                          isSelectLanguage = true;
                        },
                        dropdownButtonStyle: DropdownButtonStyle(
                          height: 50,
                          padding: const EdgeInsets.symmetric(
                            vertical: Dimensions.paddingSizeExtraSmall,
                            horizontal: Dimensions.paddingSizeExtraSmall,
                          ),
                          primaryColor: barFg,
                        ),
                        iconColor: barFg,
                        dropdownStyle: DropdownStyle(
                          elevation: 10,
                          borderRadius: BorderRadius.circular(Dimensions.radiusDefault),
                          padding: const EdgeInsets.all(Dimensions.paddingSizeExtraSmall),
                        ),
                        items: languageList,
                        child: Row(
                          children: [
                            SizedBox(height: 15, width: 15, child: Image.asset(isSelectLanguage ? AppConstants.languages[localizationController.selectedLanguageIndex].imageUrl! : AppConstants.languages[localizationController.selectedLanguageIndex].imageUrl!)),

                            Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 8.0),
                              child: Text(
                                isSelectLanguage ? AppConstants.languages[localizationController.selectedLanguageIndex].languageName! : AppConstants.languages[localizationController.selectedLanguageIndex].languageName!,
                                style: robotoMedium.copyWith(fontSize: Dimensions.fontSizeExtraSmall, color: barFg),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),

                    Get.find<SplashController>().configModel!.toggleRestaurantRegistration! || Get.find<SplashController>().configModel!.toggleDmRegistration! ? ConstrainedBox (
                      constraints: const BoxConstraints(minWidth: 100, maxWidth: 170),
                      child: CustomDropdown<int>(
                        onChange: (int? value, int index) {
                          if(value == 1){
                            Get.toNamed(RouteHelper.getRestaurantRegistrationRoute());
                          } else if (value == 2) {
                            Get.toNamed(RouteHelper.getDeliverymanRegistrationRoute());
                          }
                        },
                        canAddValue: false,
                        dropdownButtonStyle: DropdownButtonStyle(
                          height: 50,
                          padding: const EdgeInsets.symmetric(
                            vertical: Dimensions.paddingSizeExtraSmall,
                            horizontal: Dimensions.paddingSizeExtraSmall,
                          ),
                          primaryColor: barFg,
                        ),
                        iconColor: barFg,
                        dropdownStyle: DropdownStyle(
                          elevation: 10,
                          borderRadius: BorderRadius.circular(Dimensions.radiusDefault),
                          padding: const EdgeInsets.all(Dimensions.paddingSizeExtraSmall),
                        ),
                        items: joinUsList,
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(CupertinoIcons.person, color: barFg, size: 16,),
                            const SizedBox(width: Dimensions.paddingSizeSmall),
                            Text(AppConstants.joinDropdown[0].tr, style: robotoRegular.copyWith(fontSize: Dimensions.fontSizeExtraSmall, fontWeight: FontWeight.w100, color: barFg)),
                          ],
                        ),
                      ),

                    ) : SizedBox.shrink(),

                  ]);
                }),

                GetBuilder<ThemeController>(
                    builder: (themeController) {
                      return InkWell(
                        onTap: () => themeController.toggleTheme(),
                        child: Container(
                          decoration: BoxDecoration(
                            color: barFg.withValues(alpha: 0.2),
                            shape: BoxShape.circle,
                            border: Border.all(color: barFg.withValues(alpha: 0.55), width: 0.5),
                          ),
                          padding: const EdgeInsets.all(3),
                          child: Icon(themeController.darkTheme ? CupertinoIcons.moon_stars_fill : CupertinoIcons.sun_min_fill, size: 18, color: barFg),
                        ),
                      );
                    }
                ),

                const SizedBox(width: Dimensions.paddingSizeSmall),

              ]),
            ),
          ),
        ),

        Container(
          width: double.infinity,
          color: barBg,
          padding: const EdgeInsets.all(Dimensions.paddingSizeSmall),
          child: Center(child: SizedBox(width: Dimensions.webMaxWidth, child: Row(children: [
            InkWell(
              onTap: () => Get.toNamed(RouteHelper.getInitialRoute()),
              child: Image.asset(Images.logo, height: 40, width: 40, fit: BoxFit.contain),
            ),

            const SizedBox(width: Dimensions.paddingSizeLarge),

            Row(
              children: [
                MenuButton(title: 'home'.tr, onTap: () {
                  Get.toNamed(RouteHelper.getInitialRoute());
                }, baseColor: barFg, hoverColor: barFg.withValues(alpha: 0.85)),
                const SizedBox(width: 20),

                MenuButton(title: 'categories'.tr, onTap: () {
                  Get.toNamed(RouteHelper.getCategoryRoute());
                }, baseColor: barFg, hoverColor: barFg.withValues(alpha: 0.85)),
                const SizedBox(width: 20),

                MenuButton(title: 'cuisines'.tr, onTap: () {
                  Get.toNamed(RouteHelper.getCuisineRoute());
                }, baseColor: barFg, hoverColor: barFg.withValues(alpha: 0.85)),
                const SizedBox(width: 20),

                MenuButton(title: 'restaurants'.tr, onTap: () {
                  Get.toNamed(RouteHelper.getAllRestaurantRoute('popular'));
                }, baseColor: barFg, hoverColor: barFg.withValues(alpha: 0.85)),
                const SizedBox(width: 20),

                AuthHelper.isLoggedIn() ? Padding(
                  padding: const EdgeInsets.only(right: 20),
                  child: MenuButton(
                      title: 'favourite'.tr,
                      onTap: () {
                    if(AddressHelper.getAddressFromSharedPref() != null) {
                      Get.toNamed(RouteHelper.getFavouriteScreen());
                    } else {
                      showCustomSnackBar('please_select_address_first'.tr,);
                    }
                  }, baseColor: barFg, hoverColor: barFg.withValues(alpha: 0.85)),
                ) : const SizedBox(),
              ],
            ),
            const Expanded(child: SizedBox()),

            MenuIconButton(icon: CupertinoIcons.search, onTap: () => Get.toNamed(RouteHelper.getSearchRoute()), lightForeground: barFg),
            const SizedBox(width: Dimensions.paddingSizeLarge),

            MenuIconButton(icon: CupertinoIcons.bell_fill, onTap: () => Get.toNamed(RouteHelper.getNotificationRoute()), lightForeground: barFg),
            const SizedBox(width: Dimensions.paddingSizeLarge),

            MenuIconButton(icon: Icons.shopping_cart, isCart: true, lightForeground: barFg, onTap: () {
              if(Get.currentRoute.contains(RouteHelper.checkout)) {
                return Get.offNamed(RouteHelper.getCartRoute(fromDineIn: fromDineIn));
              } else {
                return Get.toNamed(RouteHelper.getCartRoute(fromDineIn: fromDineIn));
              }
            }),
            const SizedBox(width: Dimensions.paddingSizeLarge),

            GetBuilder<AuthController>(builder: (authController) {
              return InkWell(
                onTap: () {
                  if (authController.isLoggedIn()) {
                    Get.toNamed(RouteHelper.getProfileRoute());
                  }else{
                    Get.dialog(const Center(child: AuthDialogWidget(exitFromApp: false, backFromThis: false)), barrierDismissible: false,);
                  }
                },
                child: Container(
                  height: 40,
                  padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeLarge),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(Dimensions.radiusSmall),
                  ),
                  child: Row(children: [
                    Icon(authController.isLoggedIn() ? CupertinoIcons.person_crop_square : CupertinoIcons.lock, size: 18, color: barFg),
                    const SizedBox(width: Dimensions.paddingSizeSmall),
                    Text(authController.isLoggedIn() ? 'profile'.tr : 'sign_in'.tr, style: robotoRegular.copyWith(fontSize: Dimensions.fontSizeSmall, fontWeight: FontWeight.w100, color: barFg)),
                  ]),
                ),
              );
            }),

            MenuIconButton(icon: Icons.menu, lightForeground: barFg, onTap: () {
              Scaffold.of(context).openEndDrawer();
            }),
          ]),
          )),
        ),

      ],
    );
  }
  @override
  Size get preferredSize =>  const Size(Dimensions.webMaxWidth, 100);
}


class MenuButton extends StatelessWidget {
  final String title;
  final Function onTap;
  final Color? baseColor;
  final Color? hoverColor;

  const MenuButton({super.key, required this.title, required this.onTap, this.baseColor, this.hoverColor});

  @override
  Widget build(BuildContext context) {
    return TextHoverWidget(builder: (hovered) {
      return InkWell(
        onTap: onTap as void Function()?,
        child: Text(title, style: robotoRegular.copyWith(
          color: hovered
              ? (hoverColor ?? Theme.of(context).primaryColor)
              : (baseColor ?? Theme.of(context).textTheme.bodyLarge?.color),
          fontWeight: hovered && baseColor != null ? FontWeight.w600 : null,
        )),
      );
    });
  }
}


class MenuIconButton extends StatelessWidget {
  final IconData icon;
  final bool isCart;
  final Function onTap;
  final Color? lightForeground;

  const MenuIconButton({super.key, required this.icon, this.isCart = false, required this.onTap, this.lightForeground});

  @override
  Widget build(BuildContext context) {
    return TextHoverWidget(builder: (hovered) {
      final Color iconColor;
      if (lightForeground != null) {
        iconColor = hovered ? lightForeground!.withValues(alpha: 0.88) : lightForeground!;
      } else {
        iconColor = hovered ? Theme.of(context).primaryColor : Theme.of(context).textTheme.bodyLarge!.color!;
      }

      return IconButton(
        onPressed: onTap as void Function()?,
        icon: GetBuilder<CartController>(builder: (cartController) {
          return Stack(clipBehavior: Clip.none, children: [
            Icon(icon, color: iconColor),
            (isCart && cartController.cartList.isNotEmpty) ? Positioned(
              top: -5, right: -5,
              child: Container(
                height: 15, width: 15, alignment: Alignment.center,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: lightForeground != null ? lightForeground! : Theme.of(context).primaryColor,
                ),
                child: Text(
                  cartController.cartList.length.toString(),
                  style: robotoRegular.copyWith(
                    fontSize: 12,
                    color: lightForeground != null ? Theme.of(context).primaryColor : Theme.of(context).cardColor,
                  ),
                ),
              ),
            ) : const SizedBox()
          ]);
        }),
      );
    });
  }
}

